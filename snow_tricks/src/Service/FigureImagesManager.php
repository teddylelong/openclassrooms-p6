<?php

namespace App\Service;

use App\Entity\FigureImages;
use App\Repository\FigureImagesRepository;

class FigureImagesManager
{
    private $figureImagesRepository;

    public function __construct(FigureImagesRepository $figureImagesRepository)
    {
        $this->figureImagesRepository = $figureImagesRepository;
    }

    /**
     * Add a FigureImage in DB
     *
     * @param FigureImages $figureImages
     * @return void
     */
    public function add(FigureImages $figureImages): void
    {
        $this->figureImagesRepository->add($figureImages);
    }

    /**
     * Remove a figure image from DB
     *
     * @param FigureImages $figureImages
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(FigureImages $figureImages): void
    {
        $this->figureImagesRepository->remove($figureImages);
    }

}