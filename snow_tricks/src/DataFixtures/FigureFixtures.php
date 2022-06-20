<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $userRef = [
            'admin',
            'modo',
            'user',
        ];

        $categoryRef = [
            'nonClasse',
            'debutant',
            'intermediaire',
            'avance',
            'expert',
        ];

        for ($i = 0; $i < 60; $i++) {

            $user = array_rand($userRef);
            $cat = array_rand($categoryRef);

            $figure = (new Figure())
                ->setName(ucfirst($faker->unique()->words(mt_rand(1, 3), true)))
                ->setDescription($faker->paragraphs(mt_rand(4, 8), true))
                ->setStatus(Figure::STATUS_ACCEPTED)
                ->setUser($this->getReference($userRef[$user]))
                ->setCategory($this->getReference($categoryRef[$cat]))
            ;
            $this->addReference('figure-'.$i, $figure);

            $manager->persist($figure);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
