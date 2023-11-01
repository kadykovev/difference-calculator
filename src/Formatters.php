<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;

function format(array $diff, string $format): string
{
    $result = '';

    if ($format === 'stylish') {
        $result = stylish($diff);
    } elseif ($format === 'plain') {
        $result = plain($diff);
    }

    return $result;
}

function toString(mixed $value): string
{
     $value = trim(var_export($value, true), "'");

     return $value === 'NULL' ? 'null' : $value;
}
