<?php

namespace App\DataFixtures;

use App\Entity\FigureImages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Mmo\Faker\PicsumProvider;

class FigureImagesFixtures extends Fixture implements DependentFixtureInterface
{
    private $targetDirectory;

    /**
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new PicsumProvider($faker));

        for ($i = 0; $i < 60; $i++) {

            $nbImg = mt_rand(1, 2);
            for ($x = 0; $x < $nbImg; $x++) {
                $image = (new FigureImages())
                    ->setFilename($faker->picsum($this->getTargetDirectory() . '/images', 900, 400, false))
                    ->setFigure($this->getReference('figure-' . $i));
                $manager->persist($image);
            }
        }

        $manager->flush();
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return [
            FigureFixtures::class,
        ];
    }
}
