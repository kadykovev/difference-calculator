<?php

namespace Differ\Differ;

use function Differ\Parser\parse;

function genDiff(string $firstFile, string $secondFile): string
{
    $firstFileArray = get_object_vars(parse($firstFile));
    $secondFileArray = get_object_vars(parse($secondFile));

    $keys = array_unique(array_merge(array_keys($firstFileArray), array_keys($secondFileArray)));
    sort($keys);

    $result = array_map(function ($key) use ($firstFileArray, $secondFileArray) {
        return getDiff((string) $key, $firstFileArray, $secondFileArray);
    }, $keys);

    return "{\n" . implode("\n", $result) . "\n}";
}

function toString(mixed $value): string
{
    return $value === true ? "true" : ($value === false ? "false" : (string) $value);
}

function getDiff(string $key, array $arr1, array $arr2): string
{
    if (isset($arr1[$key]) && isset($arr2[$key])) {
        return $arr1[$key] === $arr2[$key]
            ? "    {$key}: " . toString($arr1[$key])
            : "  - {$key}: " . toString($arr1[$key]) . "\n  + {$key}: " . toString($arr2[$key]);
    } elseif (isset($arr1[$key])) {
        return "  - {$key}: " . toString($arr1[$key]);
    } else {
        return "  + {$key}: " . toString($arr2[$key]);
    }
}
