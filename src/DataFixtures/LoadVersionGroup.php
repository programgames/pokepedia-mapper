<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\VersionGroupApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadVersionGroup extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    private VersionGroupApi $versionGroupApi;

    public function __construct(VersionGroupApi $versionGroupApi)
    {
        $this->versionGroupApi = $versionGroupApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->versionGroupApi->getVersionGroups() as $versionGroup) {
            $manager->persist($versionGroup);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadGeneration::class];
    }
}
