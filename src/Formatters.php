<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function format(array $diff, string $format): string
{
    $result = '';

    if ($format === 'stylish') {
        $result = stylish($diff);
    } elseif ($format === 'plain') {
        $result = plain($diff);
    } elseif ($format === 'json') {
        $result = json($diff);
    } else {
        throw new \Exception('Invalid format! Use "stylish", "plain" or "json"');
    }

    return $result;
}
