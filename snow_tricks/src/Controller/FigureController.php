<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Figure;
use App\Entity\FigureImages;
use App\Entity\FigureMedias;
use App\Form\FigureType;
use App\Security\Voter\FigureVoter;
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
     * @Route("/figure/show/{id<\d+>}-{slug}", name="app_figure_show", methods={"GET"})
     */
    public function show(Figure $figure): Response
    {
        return $this->render('figure/show.html.twig', [
           'figure' => $figure
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
     * @Route("/category/{id<\d+>}-{slug})", name="app_figures_by_category")
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
