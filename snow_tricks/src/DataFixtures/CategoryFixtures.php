<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $nonClasse = (new Category())
            ->setName('Non-classé')
            ->setSlug('non-classe')
            ->setIsDefault(1)
        ;
        $manager->persist($nonClasse);

        $debutant = (new Category())
            ->setName('Débutant')
            ->setSlug('debutant')
        ;
        $manager->persist($debutant);

        $intermediaire = (new Category())
            ->setName('Intermédiaire')
            ->setSlug('intermediaire');
        ;
        $manager->persist($intermediaire);

        $avance = (new Category())
            ->setName('Avancé')
            ->setSlug('avance')
        ;
        $manager->persist($avance);

        $expert = (new Category())
            ->setName('Expert')
            ->setSlug('expert')
        ;
        $manager->persist($expert);

        $manager->flush();
    }
}
