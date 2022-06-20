<?php

namespace App\DataFixtures;

use App\Entity\FigureMedias;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FigureMediasFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $iframeList = [
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/mBB7CznvSPQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/PxhfDec8Ays" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/jP2RH7Y1FuE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/18ieZvxSBPk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/3Oy5u7iny18" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/83TKev8m380" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/GS9MMT_bNn8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/nHMjX8RL3Oc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        ];

        $iframe = array_rand($iframeList);

        for ($i = 0; $i < 60; $i++) {

            $nbMedias = mt_rand(1, 2);
            for ($x = 0; $x < $nbMedias; $x++) {
                $media = (new FigureMedias())
                    ->setUrl($iframeList[$iframe])
                    ->setFigure($this->getReference('figure-' . $i))
                ;
                $manager->persist($media);
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
