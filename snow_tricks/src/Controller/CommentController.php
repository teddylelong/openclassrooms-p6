<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Figure;
use App\Security\Voter\AdminVoter;
use App\Security\Voter\CommentVoter;
use App\Service\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="app_comment")
     */
    public function index(CommentManager $commentManager): Response
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW);

        $comments = $commentManager->findAll();

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/comment/loadmore/{id<\d+>}/{page<\d+>}")
     */
    public function loadMore(Figure $figure, CommentManager $commentManager, $page = 1): Response
    {
        $commentsPerPage = 10;
        $beginAt = ($page - 1) * $commentsPerPage;

        $content = $this->render('_parts/comments.part.twig', [
            'comments' => $commentManager->findByFigureAndStatusOrderByDateLimit($figure, Comment::STATUS_ACCEPTED, $commentsPerPage, $beginAt)
        ])->getContent();

        return new Response($content, 200, array('Content-Type' => 'text/html'));
    }

    /**
     * @Route("/admin/comment/status/{id<\d+>}/{status}", name="app_comment_update_status")
     */
    public function updateStatus(Comment $comment, CommentManager $commentManager, $status): Response
    {
        $this->denyAccessUnlessGranted(CommentVoter::UPDATE, $comment);

        $checkedComment = $commentManager->checkStatus($status);

        if ($checkedComment) {
            $comment->setStatus($checkedComment['status']);
            $commentManager->add($comment);

            $this->addFlash('success', "Le commentaire a bien été {$checkedComment['label']}.");

            return $this->redirectToRoute('app_comment');
        }

        $this->addFlash('danger', "Le status du commentaire n'est pas valide. Veuillez réessayer.");

        return $this->redirectToRoute('app_comment');
    }

    /**
     * @Route("/comment/delete/{id<\d+>}", name="app_comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, CommentManager $commentManager): Response
    {
        $this->denyAccessUnlessGranted(CommentVoter::DELETE, $comment);

        $figure = $comment->getFigure();

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentManager->delete($comment);
            $this->addFlash('success', "Le commentaire a été supprimé avec succès");
        }

        return $this->redirectToRoute('app_figure_show', [
            'id' => $figure->getId(),
            'slug' => $figure->getSlug(),
        ]);
    }
}
