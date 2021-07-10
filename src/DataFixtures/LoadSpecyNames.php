<?php


namespace App\DataFixtures;


use App\Api\PokeAPI\GenerationApi;
use App\Api\PokeAPI\MoveApi;
use App\Api\PokeAPI\PokemonSpecyNameApi;
use App\Api\PokeAPI\VersionGroupApi;
use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadSpecyNames extends Fixture implements DependentFixtureInterface
{
    private PokemonSpecyNameApi $pokemonSpecyNameApi;

    public function __construct(PokemonSpecyNameApi $pokemonSpecyNameApi)
    {
        $this->pokemonSpecyNameApi = $pokemonSpecyNameApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->pokemonSpecyNameApi->getFrenchSpecyNames() as $moveName) {
            $manager->persist($moveName);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemonSpecies::class];
    }
}