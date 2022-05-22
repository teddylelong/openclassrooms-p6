<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Figure;
use App\Security\Voter\CommentVoter;
use App\Service\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
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
