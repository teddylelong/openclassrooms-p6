<?php

namespace App\EntityListener;

use App\Entity\Category;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryEntityListener
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
     * @param Category $category
     * @return void
     */
    public function prePersist(Category $category)
    {
        $category->computeSlug($this->slugger);
    }

    /**
     * @param Category $category
     * @return void
     */
    public function preUpdate(Category $category)
    {
        $category->computeSlug($this->slugger);
    }
}