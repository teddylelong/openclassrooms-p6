<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\FigureImages;
use App\Entity\FigureMedias;
use App\Form\CommentType;
use App\Form\FigureType;
use App\Security\Voter\FigureVoter;
use App\Service\CommentManager;
use App\Service\FigureManager;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure", name="app_figure_index")
     */
    public function index(FigureManager $figureManager): Response
    {
        return $this->render('figure/index.html.twig', [
            'figures' => $figureManager->findByStatusOrderByDate(),
        ]);
    }

    /**
     * @Route("/admin/figures/approvement/", name="app_figure_approvement")
     */
    public function indexApprovement(FigureManager $figureManager): Response
    {
        return $this->render('figure/approvement.html.twig', [
            'figures' => $figureManager->findAllOrderByDate(),
        ]);
    }

    /**
     * @Route("/figure/show/{id<\d+>}-{slug}", name="app_figure_show")
     */
    public function show(Request $request, Figure $figure, CommentManager $commentManager): Response
    {
        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);
        $comment = new Comment();

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $comment->setContent($commentForm->getData()->getContent());
            $comment->setUser($this->getUser());
            $comment->setFigure($figure);

            $message = "Votre commentaire a bien été enregistré. Il sera vérifié par un membre de l'équipe d'ici deux jours ouvrés. Merci ! :)";

            if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MODO')) {
                $comment->setStatus(Comment::STATUS_ACCEPTED);
                $message = "Votre commentaire à été publié avec succès !";
            }

            $commentManager->add($comment);

            $this->addFlash('success', $message);

            return $this->redirectToRoute('app_figure_show', [
                'id' => $figure->getId(),
                'slug' => $figure->getSlug(),
            ]);
        }

        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'comments' => $commentManager->findByFigureAndStatus($figure),
            'commentForm' => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/figure/new", name="app_figure_new")
     */
    public function new(Request $request, FigureManager $figureManager, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $media = new FigureMedias(); // Add a new blank media field
        $figure = new Figure();
        $figure->addFigureMedia($media);

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                /** @var UploadedFile $image */
                $fileName = $fileUploader->upload($image);

                $figureImage = new FigureImages();
                $figureImage->setFilename($fileName);

                $figure->addFigureImage($figureImage);
            }

            $user = $this->getUser();
            $figure->setUser($user);

            $figureManager->add($figure);

            $this->addFlash('success', "Votre figure a bien été enregistrée. Elle sera relue et vérifiée par un administrateur d'ici deux jours ouvrés. Merci ! :)");

            return $this->redirectToRoute('app_figure_index');
        }
        return $this->render('figure/new.html.twig', [
            'figureType' => $form->createView()
        ]);
    }

    /**
     * @Route("/figure/edit/{id<\d+>}-{slug}/", name="app_figure_edit")
     */
    public function edit(Request $request, Figure $figure, FigureManager $figureManager, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted(FigureVoter::EDIT, $figure);

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                /** @var UploadedFile $image */
                $fileName = $fileUploader->upload($image);

                $figureImage = new FigureImages();
                $figureImage->setFilename($fileName);

                $figure->addFigureImage($figureImage);
            }
            $figure->setUpdatedAt(new \DateTimeImmutable());
            $figure->setStatus(Figure::STATUS_PENDING);

            $figureManager->add($figure);

            $this->addFlash('success', "La figure a été mise à jour avec succès ! Elle sera relue et vérifiée par un administrateur d'ici deux jours ouvrés.");

            return $this->redirectToRoute('app_figure_index');
        }

        return $this->render('figure/edit.html.twig', [
            'figure' => $figure,
            'editForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/figure/status/{id<\d+>}/{status}", name="app_figure_update_status")
     */
    public function updateStatus(Figure $figure, FigureManager $figureManager, $status): Response
    {
        $this->denyAccessUnlessGranted(FigureVoter::UPDATE_STATUS, $figure);

        // @Todo : à valider !
        switch ($status) {
            case 'accept':
                $figure->setStatus(Figure::STATUS_ACCEPTED);
                $label = "validée";
                break;

            case 'refuse':
                $figure->setStatus(Figure::STATUS_REJECTED);
                $label = "refusée";
                break;

            case 'pending':
                $figure->setStatus(Figure::STATUS_PENDING);
                $label = "mise en attente";
                break;

            default:
                $this->addFlash('danger', "Le statut de la figure n'est pas valide.");
                return $this->redirectToRoute('app_figure_approvement');
        }
        $figureManager->add($figure);
        $this->addFlash('success', "La figure a bien été $label.");

        return $this->redirectToRoute('app_figure_approvement');
    }

    /**
     * @Route("/category/{id<\d+>}-{slug}", name="app_figures_by_category")
     */
    public function indexByCategory(Request $request, Category $category, FigureManager $figureManager): Response
    {
        return $this->render('figure/index_by_category.html.twig', [
            'category' => $category,
            'figures' => $figureManager->findAllByCategoryOrderByDate($category->getId()),
        ]);
    }

    /**
     * @Route("/figure/delete/{id<\d+>}", name="app_figure_delete", methods={"POST"})
     */
    public function delete(Request $request, Figure $figure, FigureManager $figureManager): Response
    {
        $this->denyAccessUnlessGranted(FigureVoter::DELETE, $figure);

        if ($this->isCsrfTokenValid('delete'.$figure->getId(), $request->request->get('_token'))) {
            $figureManager->delete($figure);
            $this->addFlash('success', "La figure a été supprimée avec succès");
        }

        return $this->redirectToRoute('app_figure_index');
    }
}
