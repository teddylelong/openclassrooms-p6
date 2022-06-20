<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $userRef = [
            'admin',
            'modo',
            'user',
        ];

        for ($i = 0; $i < 60; $i++) {
            $nbComment = mt_rand(0, 12);

            for ($x = 0; $x < $nbComment; $x++) {
                $user = array_rand($userRef);
                $comment = (new Comment())
                    ->setContent($faker->text(mt_rand(50, 250)))
                    ->setUser($this->getReference($userRef[$user]))
                    ->setFigure($this->getReference('figure-' . $i))
                    ->setStatus(Comment::STATUS_ACCEPTED);
                ;
                $manager->persist($comment);

            }
        }

        $manager->flush();
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
