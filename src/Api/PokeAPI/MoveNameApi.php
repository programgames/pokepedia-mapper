<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Move;
use App\Entity\MoveName;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform move names information into entities from pokeapi
class MoveNameApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getMoveNames(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_movename {
    name
    pokemon_v2_move {
      name
    }
    language_id
  }
}

GRAPHQL;

        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $moveNames = [];
        foreach ($content['data']['pokemon_v2_movename'] as $moveName) {
            $moveNameEntity = new MoveName();
            $moveNameEntity->setName($moveName['name']);
            $moveNameEntity->setLanguage($moveName['language_id']);
            $move = $this->entityManager->getRepository(Move::class)->findOneBy(
                [
                    'name' => $moveName['pokemon_v2_move']['name']
                ]
            );
            $moveNameEntity->setMove($move);
            $moveNames[] = $moveNameEntity;
        }

        return $moveNames;
    }
}
