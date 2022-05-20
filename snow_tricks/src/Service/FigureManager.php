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
     * @return Figure[] Returns an array of Figure objects
     */
    public function findByStatusOrderByDate($value = Figure::STATUS_ACCEPTED)
    {
        return $this->figureRepository->findByStatusOrderByDate($value);
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
}