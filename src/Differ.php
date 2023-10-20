<?php

namespace Differ\Differ;

function genDiff(string $firstFile, string $secondFile)
{
    $firstFilePath = realpath($firstFile);
    $secondFilePath = realpath($secondFile);

    $firstFileContent = file_get_contents($firstFilePath);
    $secondFileContent = file_get_contents($secondFilePath);

    $firstFileArray = json_decode($firstFileContent, true);
    $secondFileArray = json_decode($secondFileContent, true);

    $keys = array_unique(array_merge(array_keys($firstFileArray), array_keys($secondFileArray)));
    sort($keys);

    $result = array_map(function ($key) use ($firstFileArray, $secondFileArray) {
        return getDiff($key, $firstFileArray, $secondFileArray);
    }, $keys);

    return "{\n" . implode("\n", $result) . "\n}\n";
}

function toString($value): string
{
    return $value === true ? "true" : ($value === false ? "false" : (string) $value);
}

function getDiff($key, $arr1, $arr2)
{
    if (isset($arr1[$key]) && isset($arr2[$key])) {
        return $arr1[$key] === $arr2[$key]
            ? "    {$key}: " . toString($arr1[$key])
            : "  - {$key}: " . toString($arr1[$key]) . "\n  + {$key}: " . toString($arr2[$key]);
    } elseif (isset($arr1[$key])) {
        return "  - {$key}: " . toString($arr1[$key]);
    } else {
        return "  - {$key}: " . toString($arr2[$key]);
    }
}
