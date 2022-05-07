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
    public function add(Figure $figure)
    {
        $this->figureRepository->add($figure);
    }

    /**
     * Find all Figures from database
     *
     * @return array
     */
    public function findAll(): array
    {
        $this->figureRepository->findAll()
    }

    /**
     * Delete a figure in database
     *
     * @param Figure $figure
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Figure $figure)
    {
        $this->figureRepository->remove($figure);
    }
}