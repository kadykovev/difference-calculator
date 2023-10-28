<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $file): object
{
    $filePath = (string) realpath($file);
    $fileContent = (string) file_get_contents($filePath);
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    if ($extension === 'json') {
        return json_decode($fileContent);
    } else {
        return Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
    }
}


//$recursiveFilesExpected = file_get_contents(__DIR__ . "/fixtures/recursiveFilesExpected.txt");

/* $recursiveJSONFile1 = "/../tests/fixtures/recursiveFile1.json";
$recursiveJSONFile2 = "/../tests/fixtures/recursiveFile2.json";

$content = file_get_contents(__DIR__ . $recursiveJSONFile1);
$vars = json_decode($content);
//print_r($vars);

print_r($arr = get_object_vars($vars)); */
//print_r(array_keys($arr));
