<?php

namespace Differ\Differ;

function genDiff(string $firstFile, string $secondFile)
{
    $firstFilePath = realpath($firstFile);
    $secondFilePath = realpath($secondFile);

    $firstFileContent = file_get_contents($firstFilePath);
    $secondFileContent = file_get_contents($secondFilePath);
    //print_r(json_decode($firstFileContent, true));
    //print_r(json_decode($secondFileContent, true));

    $firstFileArray = json_decode($firstFileContent, true);
    $secondFileArray = json_decode($secondFileContent, true);

    $firstFileDiff = array_map(function ($key, $value) use ($secondFileArray) {
        $valueAsString = $value === true ? "true" : ($value === false ? "false" : $value);
        if (array_key_exists($key, $secondFileArray)) {
            return $value === $secondFileArray[$key] ? "{$key}: {$valueAsString}" : "- {$key}: {$valueAsString}";
        } else {
            return "- {$key}: {$valueAsString}";
        }
    }, array_keys($firstFileArray), $firstFileArray);

    print_r($firstFileDiff);
    print_r(strval(false));
    //echo "dsgfdb";
}
