<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;

function format(array $diff, string $format): string
{
    if ($format === 'stylish') {
        return stylish($diff);
    } elseif ($format === 'plain') {
        return plain($diff);
    }
}
