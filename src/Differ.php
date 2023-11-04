<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\format;
use function Functional\sort;

function genDiff(string $firstFile, string $secondFile, string $format = 'stylish'): string
{
    try {
        $firstParsedFile = parse($firstFile);
        $secondParsedFile = parse($secondFile);
        $diff = getDiff($firstParsedFile, $secondParsedFile);
        $result = format($diff, $format);
    } catch (\Exception $e) {
        echo $e->getMessage(), PHP_EOL;
    }

    return $result ?? '';
}

function getDiff(object $obj1, object $obj2): array
{
    $keys = getKeys($obj1, $obj2);

    return array_map(function ($key) use ($obj1, $obj2) {
        if (property_exists($obj1, $key) && property_exists($obj2, $key)) {
            if (is_object($obj1->$key) && is_object($obj2->$key)) {
                $status = 'nested';
                $children = ['children' => getDiff($obj1->$key, $obj2->$key)];
            } elseif ($obj1->$key === $obj2->$key) {
                $status = 'unchanged';
                $value = ['value' => $obj1->$key];
            } else {
                $status = 'updated';
                $value = ['oldValue' => $obj1->$key, 'newValue' => $obj2->$key];
            }
        } elseif (property_exists($obj1, $key)) {
            $status = 'removed';
            $value = ['value' => $obj1->$key];
        } else {
            $status = 'added';
            $value = ['value' => $obj2->$key];
        }

        return array_merge(['name' => $key, 'status' => $status], $value ?? [], $children ?? []);
    }, $keys);
}

function getKeys(object $obj1, object $obj2): array
{
    $keysObj1 = array_keys((array) $obj1);
    $keysObj2 = array_keys((array) $obj2);

    $keys = array_unique(array_merge($keysObj1, $keysObj2));
    $sortedKeys = sort($keys, fn($left, $right) => $left <=> $right);

    return $sortedKeys;
}
