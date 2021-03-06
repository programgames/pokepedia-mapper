<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\MoveNameApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadMoveNames extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    private MoveNameApi $moveNameApi;

    public function __construct(MoveNameApi $moveNameApi)
    {
        $this->moveNameApi = $moveNameApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->moveNameApi->getMoveNames() as $moveName) {
            $manager->persist($moveName);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadMove::class];
    }
}
