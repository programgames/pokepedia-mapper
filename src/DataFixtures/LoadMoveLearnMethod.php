<?php


namespace App\DataFixtures;


use App\Api\PokeAPI\GenerationApi;
use App\Api\PokeAPI\MoveLearnMethodApi;
use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadMoveLearnMethod extends Fixture
{
    private MoveLearnMethodApi $learnMethodApi;

    public function __construct(MoveLearnMethodApi $learnMethodApi)
    {
        $this->learnMethodApi = $learnMethodApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->learnMethodApi->getMoveLearnMethods() as $moveLearnMethod) {
            $manager->persist($moveLearnMethod);
        }
        $manager->flush();
    }
}