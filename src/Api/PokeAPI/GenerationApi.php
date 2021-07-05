<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class GenerationApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getGenerations(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_generation {
    name
  }
}

GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'generation'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $generations = [];
        foreach ($json['data']['pokemon_v2_generation'] as $generation) {
            $generationEntity = new Generation();
            $generationEntity->setName($generation['name']);
            $generations[] = $generationEntity;
        }

        return $generations;
    }
}