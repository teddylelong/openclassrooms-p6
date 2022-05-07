<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Service\FigureManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure", name="app_figure_index")
     */
    public function index(): Response
    {
        return $this->render('figure/index.html.twig', [
            'controller_name' => 'FigureController',
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
            $figure->setUserId($user);

            $figureManager->add($figure);

            $this->addFlash('success', "Votre figure a bien été enregistrée. Elle sera relue et vérifiée par un administrateur d'ici deux jours ouvrés. Merci ! :)");

            return $this->redirectToRoute('app_figure_index');
        }
        return $this->render('figure/new.html.twig', [
            'figureType' => $form->createView()
        ]);
    }
}
