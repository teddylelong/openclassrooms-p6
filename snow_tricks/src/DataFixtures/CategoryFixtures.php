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
        $this->addReference('nonClasse', $nonClasse);

        $manager->persist($nonClasse);

        $debutant = (new Category())
            ->setName('Débutant')
            ->setSlug('debutant')
        ;
        $this->addReference('debutant', $debutant);

        $manager->persist($debutant);

        $intermediaire = (new Category())
            ->setName('Intermédiaire')
            ->setSlug('intermediaire')
        ;
        $this->addReference('intermediaire', $intermediaire);

        $manager->persist($intermediaire);

        $avance = (new Category())
            ->setName('Avancé')
            ->setSlug('avance')
        ;
        $this->addReference('avance', $avance);

        $manager->persist($avance);

        $expert = (new Category())
            ->setName('Expert')
            ->setSlug('expert')
        ;
        $this->addReference('expert', $expert);

        $manager->persist($expert);

        $manager->flush();
    }
}
