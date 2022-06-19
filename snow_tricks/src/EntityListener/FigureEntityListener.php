<?php

namespace App\EntityListener;

use App\Entity\Figure;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureEntityListener
{
    private $slugger;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param Figure $figure
     * @return void
     */
    public function prePersist(Figure $figure)
    {
        $figure->computeSlug($this->slugger);
    }

    /**
     * @param Figure $figure
     * @return void
     */
    public function preUpdate(Figure $figure)
    {
        $figure->computeSlug($this->slugger);
    }
}