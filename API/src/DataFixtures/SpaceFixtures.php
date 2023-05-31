<?php

namespace App\DataFixtures;

use App\Entity\Space;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Desk;
use App\DTO\Dimensions;
use App\DTO\Position;
use App\Enum\DeskType;
use Doctrine\Common\Collections\ArrayCollection;

class SpaceFixtures extends Fixture
{
    public const SPACE_NAMES = [
        'Space1',
        'Space2',
        'Space3',
        'Space4',
        'Space5',
        'Space6',
        'Space7',
        'Space8'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SPACE_NAMES as $spaceName) {
            $space = new Space();
            $space->setName($spaceName);
            $space->setDimensions(new Dimensions(800, 800));
            $desksArray = [];

            for ($i = 0; $i < 5; $i++) {
                $desk = new Desk();
                $desk->setName($spaceName . ' Desk' . ($i + 1));
                $desk->setSpace($space);
                $x = rand(0, 800);
                $y = rand(0, 800);
                $angle = rand(0, 360);
                $position = new Position($x, $y, $angle);
                $desk->setPosition($position);
                $desksArray[] = $desk;
            }

            $space->setDesks(new ArrayCollection($desksArray));
            $manager->persist($space);
            $this->setReference($space->getName(), $space);
        }

        $manager->flush();
    }
}
