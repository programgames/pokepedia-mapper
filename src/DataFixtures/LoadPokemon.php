<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\PokemonApi;
use App\Api\PokeAPI\PokemonColorApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemon extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    private PokemonApi $pokemonApi;

    public function __construct(PokemonApi $pokemonApi)
    {
        $this->pokemonApi = $pokemonApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->pokemonApi->getPokemons() as $pokemon) {
            $manager->persist($pokemon);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemonSpecies::class];
    }
}
