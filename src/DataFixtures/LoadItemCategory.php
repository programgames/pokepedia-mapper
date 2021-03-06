<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\ItemApi;
use App\Api\PokeAPI\ItemCategoryApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadItemCategory extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private ItemCategoryApi $api;

    public function __construct(ItemCategoryApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->api->getItemCategory() as $itemCategory) {
            $manager->persist($itemCategory);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadItemPocket::class];
    }
}
