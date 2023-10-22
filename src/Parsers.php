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
