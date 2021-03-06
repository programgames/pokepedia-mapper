<?php

namespace App\Command\Installation;

use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonMoveAvailability;
use App\Entity\VersionGroup;
use App\Helper\MoveSetHelper;
use App\Helper\PokemonHelper;
use App\MoveMapper;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportLGPEMoves extends Command
{
    protected static $defaultName = 'app:import:lgpe';

    private EntityManagerInterface $em;
    private BulbapediaMovesAPI $api;
    private PokemonHelper $pokemonHelper;

    public function __construct(EntityManagerInterface $em, BulbapediaMovesAPI $api,PokemonHelper $pokemonHelper)
    {
        parent::__construct();

        $this->em = $em;
        $this->api = $api;
        $this->pokemonHelper = $pokemonHelper;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $moveMapper = new MoveMapper();
        $io = new SymfonyStyle($input, $output);

        $io->info("Importing Bulbapedia LGPE moves (~15 minutes)");

        $lgpe = $this->em->getRepository(VersionGroup::class)->findOneBy(['name' => 'lets-go']);

        $pokemonMoveAvailabilities = $this->em->getRepository(PokemonMoveAvailability::class)->findBy(['versionGroup' => $lgpe]);

        $levelup = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);
        $machine = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'machine']);

        $generation = $this->em->getRepository(Generation::class)->findOneBy(
            [
                'generationIdentifier' => 7
            ]
        );

        foreach ($pokemonMoveAvailabilities as $pokemonAvailability) {
            /** @var Pokemon $pokemon */
            $pokemon = $pokemonAvailability->getPokemon();
            if (false !== strpos($pokemon->getName(), "alola")) {
                //Alola Moves are mapped automatically as there is the same bulbapedia page that the original pkm
                continue;
            }
            $io->info(sprintf('import levelup moves for LGPE %s', $pokemon->getName()));
            $moves = $this->api->getLevelMoves($pokemon, 7, true);
            if (array_key_exists('noform', $moves)) {
                foreach ($moves['noform'] as $move) {
                    if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                        $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $levelup);
                    } else {
                        throw new RuntimeException('Format roman');
                    }
                }
                $this->em->flush();
            } else {
                foreach ($moves as $form => $formMoves) {
                    $pokemon = $this->pokemonHelper->findPokemonByFormName($pokemon, $form,9);
                    foreach ($formMoves as $move) {
                        if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                            $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $levelup);
                        } else {
                            throw new RuntimeException('Format roman');
                        }
                    }
                }
                $this->em->flush();
            }
        }

        foreach ($pokemonMoveAvailabilities as $pokemonAvailability) {
            $pokemon = $pokemonAvailability->getPokemon();
            if (false !== strpos($pokemon->getName(), "alola") || $pokemon->getName() === 'mew') {
                continue;
            }
            $io->info(sprintf('import machine moves for LGPE %s', $pokemon->getName()));
            $moves = $this->api->getMachineMoves($pokemon, 7, true);
            if (array_key_exists('noform', $moves)) {
                foreach ($moves['noform'] as $move) {
                    if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                        $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $machine);
                    } else {
                        throw new RuntimeException('Format roman');
                    }
                }
                $this->em->flush();
            } else {
                foreach ($moves as $form => $formMoves) {
                    $pokemon = $this->pokemonHelper->findPokemonByFormName($pokemon, $form,9);
                    foreach ($formMoves as $move) {
                        if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                            $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $machine);
                        } else {
                            throw new RuntimeException('Format roman');
                        }
                    }
                }
                $this->em->flush();
            }
        }

        $io->info("Bulbapedia LGPE moves imported");

        return Command::SUCCESS;
    }
}
