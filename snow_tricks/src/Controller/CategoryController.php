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

    /**
     * @Route("/category/edit/{id<\d+>}", name="app_category_edit")
     */
    public function edit(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());

            $categoryManager->add($category);

            $this->addFlash('success', "La catégorie {$category->getName()} a été mise à jour avec succès !");

            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/edit.html.twig', [
            'categoryType' => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * @Route("/category/delete/{id<\d+>}", name="app_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryManager->delete($category);
            $this->addFlash('success', "La catégorie {$category->getName()} a été supprimée avec succès");
        }

        return $this->redirectToRoute('app_category');
    }
}
