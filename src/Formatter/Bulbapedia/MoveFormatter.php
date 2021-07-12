<?php

namespace App\Formatter\Bulbapedia;


use App\Exception\WrongLearnListFormat;

class MoveFormatter
{
    public function formatLearnlist(string $move, int $generation, string $type)
    {
        if (preg_match(sprintf('/%s\dnull/',$type), $move)) {
            $return = [
                'format' => 'empty',
                'value' => null,
                'gen' => $generation
            ];
        }

        if (preg_match(sprintf('/%s\d+.*/',$type), $move)) {
            return [
                'format' => 'numeral',
                'value' => explode('|', $move),
                'gen' => $generation
            ];
        }
        if (preg_match(sprintf('/%s[XVI]+.*/',$type), $move)) {
            return [
                'format' => 'roman',
                'value' => explode('|', $move),
                'gen' => $generation
            ];
        }

        throw new WrongLearnListFormat('Invalid learnlist: ' . $move);
    }
}