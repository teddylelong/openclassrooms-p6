<?php

namespace App\Service;

use App\Entity\Figure;
use App\Repository\FigureRepository;

class FigureManager
{
    private $figureRepository;

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
    public function findByStatusOrderByDateLimit($value = Figure::STATUS_ACCEPTED, int $max = 10)
    {
        return $this->figureRepository->findByStatusOrderByDateLimit($value, $max);
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