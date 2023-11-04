<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function format(array $diff, string $format): string
{
    if ($format === 'stylish') {
        return stylish($diff);
    } elseif ($format === 'plain') {
        return plain($diff);
    } elseif ($format === 'json') {
        return json($diff);
    } else {
        throw new \Exception('Invalid format! Use "stylish", "plain" or "json"');
    }
}
