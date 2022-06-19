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

        for ($i = 0; $i < 20; $i++) {

            $user = array_rand($userRef);

            $figure = (new Figure())
                ->setName(ucfirst($faker->unique()->words(2, true)))
                ->setSlug($faker->unique()->slug(2))
                ->setDescription($faker->paragraphs(5, true))
                ->setStatus(Figure::STATUS_ACCEPTED)
                ->setUser($this->getReference($userRef[$user]))
                ->setCategory(null)
            ;

            $manager->persist($figure);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}
