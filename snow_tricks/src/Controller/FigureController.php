<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Service\FigureManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureController extends AbstractController
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
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
     * @Route("/figure/show/{id<\d+>}", name="app_figure_show", methods={"GET"})
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
    public function new(Request $request, FigureManager $figureManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $figure->setUser($user);
            $figure->setSlug(strtolower($this->slugger->slug($figure->getName())));

            $figureManager->add($figure);

            $this->addFlash('success', "Votre figure a bien été enregistrée. Elle sera relue et vérifiée par un administrateur d'ici deux jours ouvrés. Merci ! :)");

            return $this->redirectToRoute('app_figure_index');
        }
        return $this->render('figure/new.html.twig', [
            'figureType' => $form->createView()
        ]);
    }

    /**
     * @Route("/figure/edit/{id<\d+>}/", name="app_figure_edit")
     */
    public function edit(Request $request, Figure $figure, FigureManager $figureManager)
    {
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setUpdatedAt(new \DateTimeImmutable());
            $figure->setSlug(strtolower($this->slugger->slug($figure->getName())));
            $figure->setStatus(Figure::STATUS_PENDING);

            $figureManager->add($figure);

            $this->addFlash('success', "La figure a été mise à jour avec succès ! Elle sera relue et vérifiée par un administrateur d'ici deux jours ouvrés.");

            return $this->redirectToRoute('app_figure_index');
        }

        return $this->renderForm('figure/edit.html.twig', [
            'figure' => $figure,
            'editForm' => $form
        ]);
    }

    /**
     * @Route("/figure/delete/{id<\d+>}", name="app_figure_delete", methods={"POST"})
     */
    public function delete(Request $request, Figure $figure, FigureManager $figureManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$figure->getId(), $request->request->get('_token'))) {
            $figureManager->delete($figure);
            $this->addFlash('success', "La figure a été supprimée avec succès");
        }

        return $this->redirectToRoute('app_figure_index');
    }
}
