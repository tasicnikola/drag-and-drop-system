<?php

namespace App\DataFixtures;

use App\DTO\Position;
use App\Entity\Desk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DeskFixtures extends Fixture implements DependentFixtureInterface
{
    public const DESK_NAMES = ['desk0', 'desk1', 'desk2'];

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 3; $i++) {
            $desk = new Desk();
            $desk->setName('desk' . $i);
            $desk->setSpace($this->getReference(SpaceFixtures::SPACE_NAMES[array_rand(SpaceFixtures::SPACE_NAMES)]));
            $x = rand(0, 800);
            $y = rand(0, 800);
            $angle = rand(0, 360);
            $position = new Position($x, $y, $angle);
            $desk->setPosition($position);
            $manager->persist($desk);

            $this->setReference($desk->getName(), $desk);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SpaceFixtures::class
        ];
    }
}
