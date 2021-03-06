<?php

namespace App\Satanizer;

use App\Exception\WrongFooterException;
use App\Exception\WrongHeaderException;
use App\Exception\WrongLearnListFormat;
use App\Formatter\Bulbapedia\MoveFormatter;

// Extract pokemon moves from bulbapedia
class BulbapediaMoveSatanizer
{
    private MoveFormatter $moveFormatter;

    public function __construct(MoveFormatter $moveFormatter)
    {
        $this->moveFormatter = $moveFormatter;
    }

    public function checkAndSanitizeMoves(array $moves, int $generation, string $type): array
    {
        $formattedMoves = [];

        $moves = $this->clearPadding($moves);
        $movesSize = count($moves);

        if (!in_array(
            $moves[0],
            [
                '====By [[Level|leveling up]]====',
                '====By [[level|leveling up]]====',
                '=====By [[Level|leveling up]]=====',
                '====By [[Move Tutor|tutoring]]====',
                '=====By [[TM]]=====',
                '====By [[TM]]====',
                '====By [[TM]]/[[HM]]====',
                '====By [[TM]]/[[TR]]====',
                '====By {{pkmn|breeding}}===='
            ]
        )) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        }
        if (preg_match('/=====.*=====/', $moves[1])) {
            return $this->handleFormMoves($moves, $generation, $type);
        }

        if (!preg_match(sprintf('/learnlist\/%sh.*/', $type), $moves[1])) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[1]));
        }

        for ($i = 2; $i < $movesSize - 1; $i++) {
            if (preg_match(sprintf('/learnlist\/%s\dnull/', $type), $moves[$i])) {
                $movesByForm['noform'] = $formattedMoves;

                return $movesByForm;
            }
            if (!preg_match(sprintf('/learnlist\/%s\d+.*/', $type), $moves[$i])
                && !preg_match(sprintf('/learnlist\/tr.*/'), $moves[$i])
                && !preg_match(sprintf('/learnlist\/%s[XVI]+.*/', $type), $moves[$i])) {
                throw new WrongLearnListFormat(sprintf('Invalid learnlist: %s', $moves[$i]));
            }
            $formattedMoves[$i] = $this->moveFormatter->formatLearnlist($moves[$i], $generation, $type);
        }
        if (!preg_match(sprintf('/learnlist\/%sf.*/', $type), $moves[$i])) {
            throw new WrongFooterException(sprintf('Invalid footer: %s', $moves[1]));
        }

        $movesByForm['noform'] = $formattedMoves;

        return $movesByForm;
    }

    private function handleFormMoves(array $moves, int $generation, string $type): array
    {
        $movesByForms = [];
        $size = count($moves);
        $form = null;
        array_shift($moves);

        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i < $size; $i++) {
            if (empty($moves[$i]) || $moves[$i] === ' ') {
                continue;
            }

            if (!$form && preg_match('/=====.*=====/', $moves[$i])) {
                $form = str_replace('=', '', $moves[$i]);
                if (!preg_match(sprintf('/learnlist\/%sh.*/', $type), $moves[$i + 1])) {
                    throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[1]));
                }
                $i++;
                continue;
            }

            if (preg_match(sprintf('/learnlist\/%s\dnull/', $type), $moves[$i])) {
                $movesByForms[$form] = [];
                continue;
            }
            if ($form && (preg_match(sprintf('/learnlist\/%s\d+.*/', $type), $moves[$i])
                    || preg_match(sprintf('/learnlist\/tr.*/'), $moves[$i])
                    || preg_match(sprintf('/learnlist\/%s[XVI]+.*/', $type), $moves[$i]))) {
                $movesByForms[$form][] = $this->moveFormatter->formatLearnlist($moves[$i], $generation, $type);
            } elseif (preg_match(sprintf('/learnlist\/%sf.*/', $type), $moves[$i])) {
                $form = null;
            } else {
                throw new WrongLearnListFormat(sprintf('Invalid learnlist: %s', $moves[$i]));
            }
        }
        return $movesByForms;
    }

    private function clearPadding(array $moves): array
    {
        foreach ($moves as $key => $move) {
            $moves[$key] = str_replace('Learnlist','learnlist',$move);
        }
        $size = count($moves);

        for ($i = $size -1; $i > 0; $i--) {
            if (false === strpos($moves[$i], "learnlist") &&  false === strpos($moves[$i], "====")) {
                unset($moves[$i]);
            }
        }

        return array_values($moves);
    }
}
