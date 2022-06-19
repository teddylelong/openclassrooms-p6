<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Figure;
use App\Repository\FigureRepository;

class FigureManager
{
    private $figureRepository;

    /**
     * @param FigureRepository $figureRepository
     */
    public function __construct(FigureRepository $figureRepository)
    {
        $this->figureRepository = $figureRepository;
    }

    /**
     * Add a figure in database
     *
     * @param Figure $figure
     * @return void
     */
    public function add(Figure $figure): void
    {
        $this->figureRepository->add($figure);
    }

    /**
     * @param $status
     * @return float|int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllByStatus($status = Figure::STATUS_ACCEPTED)
    {
        return $this->figureRepository->countAllByStatus($status);
    }

    /**
     * @param Category $category
     * @param $status
     * @return float|int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllByStatusAndCategory(Category $category, $status = Figure::STATUS_ACCEPTED)
    {
        return $this->figureRepository->countAllByStatusAndCategory($category, $status);
    }

    /**
     * find all figures, by status and ordered by dates
     *
     * @return Figure[] Returns an array of Figure objects
     */
    public function findByStatusOrderByDate($value = Figure::STATUS_ACCEPTED)
    {
        return $this->figureRepository->findByStatusOrderByDate($value);
    }

    /**
     * Find all figures, by status, ordered by dates and limit
     *
     * @return Figure[] Returns an array of Figure objects
     */
    public function findByStatusOrderByDateLimit($value = Figure::STATUS_ACCEPTED, int $max = 12, int $offset = 0)
    {
        return $this->figureRepository->findByStatusOrderByDateLimit($value, $max, $offset);
    }

    /**
     * Find all figures ordered by dates
     *
     * @return Figure[] Returns an array of Figure objects
     */
    public function findAllOrderByDate()
    {
        return $this->figureRepository->findAllOrderByDate();
    }

    /**
     * Find all figures, by category, ordered by dates
     *
     * @return Figure[] Returns an array of Figure objects
     */
    public function findAllByCategoryOrderByDate($categoryId)
    {
        return $this->figureRepository->findAllByCategoryOrderByDate($categoryId);
    }

    /**
     * Find all figures, by status and category, ordered by dates and limit
     *
     * @return Figure[] Returns an array of Figure objects
     */
    public function findAllByStatusAndCategoryOrderByDateLimit(Category $category, $status = Figure::STATUS_ACCEPTED, int $max = 12, int $offset = 0)
    {
        return $this->figureRepository->findAllByStatusAndCategoryOrderByDateLimit($category, $status, $max, $offset);
    }

    /**
     * Find all Figures from database
     *
     * @return array|null
     */
    public function findAll(): ?array
    {
        return $this->figureRepository->findAll();
    }

    /**
     * Delete a figure in database
     *
     * @param Figure $figure
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Figure $figure): void
    {
        $this->figureRepository->remove($figure);
    }

    /**
     * Check a Figure status and return right Const
     */
    public function checkStatus(string $status): ?array
    {
        switch ($status) {
            case 'accept':
                return [
                    'status' => Figure::STATUS_ACCEPTED,
                    'label' => "validée"
                ];

            case 'refuse':
                return [
                    'status' => Figure::STATUS_REJECTED,
                    'label' => "refusée"
                ];

            case 'pending':
                return [
                    'status' => Figure::STATUS_PENDING,
                    'label' => "mise en file d'attente"
                ];

            default:
                return null;
        }
    }
}