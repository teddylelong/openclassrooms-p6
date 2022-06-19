<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryManager
{
    private $repository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->repository = $categoryRepository;
    }

    /**
     * Add a Category in DB
     *
     * @param Category $category
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Category $category): void
    {
        $this->repository->add($category);
    }

    /**
     * Find all categories in DB
     *
     * @return array|null
     */
    public function findAll(): ?array
    {
        return $this->repository->findAll();
    }

    /**
     * Find all categories, order by their name
     *
     * @return array|null
     */
    public function findAllOrderByName(): ?array
    {
        return $this->repository->findAllOrderByName();
    }

    /**
     * Delete a category in DB
     *
     * @param Category $category
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Category $category): void
    {
        $this->repository->remove($category);
    }
}