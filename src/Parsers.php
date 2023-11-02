<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $file): object
{
    $filePath = (string) realpath($file);

    if (!file_exists($filePath)) {
        throw new \Exception('The file (' . $file . ') does not exists!');
    }

    $fileContent = (string) file_get_contents($filePath);
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    if ($extension === 'json') {
        return json_decode($fileContent);
    } elseif ($extension === 'yaml' || $extension === 'yml') {
        return Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception('Files with the extension "json", "yaml" or "yml" are allowed!');
    }
}
