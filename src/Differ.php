<?php

namespace Differ\Differ;

use function Functional\reduce_left;
use function Functional\map;

function genDiff(string $firstFile, string $secondFile)
{
    $firstFilePath = realpath($firstFile);
    $secondFilePath = realpath($secondFile);

    $firstFileContent = file_get_contents($firstFilePath);
    $secondFileContent = file_get_contents($secondFilePath);

    $firstFileArray = json_decode($firstFileContent, true);
    $secondFileArray = json_decode($secondFileContent, true);

    $mergedArrays = array_merge($firstFileArray, $secondFileArray);
    ksort($mergedArrays);

    $result = map($mergedArrays, function ($value, $key) use ($firstFileArray, $secondFileArray) {
        if (array_key_exists($key, $secondFileArray)) {
            if (array_key_exists($key, $firstFileArray)) {
                return $value === $firstFileArray[$key] ? "    {$key}: " . toString($value) : "  - {$key}: " . toString($firstFileArray[$key]) . "\n  + {$key}: " . toString($value);
            } else {
                return "  + {$key}: " . toString($value);
            }
        } else {
            return "  - {$key}: " . toString($value);
        }
    });

    return "{\n" . implode("\n", $result) . "\n}";
}

function toString($value): string
{
    return $value === true ? "true" : ($value === false ? "false" : (string) $value);
}
