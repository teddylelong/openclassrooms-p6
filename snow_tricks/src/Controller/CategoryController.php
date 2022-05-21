<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="app_category")
     */
    public function index(CategoryManager $categoryManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('category/index.html.twig', [
            'categories' => $categoryManager->findAllOrderByName(),
        ]);
    }

    /**
     * @Route("/category/new", name="app_category_new")
     */
    public function new(Request $request, CategoryManager $categoryManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryManager->add($category);

            $this->addFlash('success', "La catégorie {$category->getName()} a été créé avec succès !");

            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig', [
            'categoryType' => $form->createView(),
        ]);
    }
}
