<?php

namespace Differ\Formatters\Plain;

use function Differ\Formatters\toString;

function plain(array $diff, array $path = []): string
{
    return array_reduce($diff, function ($acc, $item) use ($path) {

        $name = $item['name'];
        $status = $item['status'];
        $hasChildren = isset($item['children']) ? true : false;
        $path[] = $name;

        if ($hasChildren) {
            $children = $item['children'];
            $acc .= plain($children, $path);
        } else {
            if ($status === 'updated') {
                $oldValue = getFormattedValue($item['oldValue']);
                $newValue = getFormattedValue($item['newValue']);
                $acc .= displayPath($path) . "' was updated. From " . $oldValue . " to " . $newValue . PHP_EOL;
            } else {
                $value = getFormattedValue($item['value']);
                if ($status === 'added') {
                    $acc .= displayPath($path) . "' was added with value: " . $value . PHP_EOL;
                } elseif ($status === 'removed') {
                    $acc .= displayPath($path) . "' was removed" . PHP_EOL;
                }
            }
        }

        return $acc;
    });
}

function getFormattedValue(mixed $value): string
{
    $type = gettype($value);

    if ($type === 'object' || $type === 'array') {
        return '[complex value]';
    } elseif ($type === 'string') {
        return "'" . toString($value) . "'";
    } else {
        return toString($value);
    }
}

function displayPath(array $path): string
{
    return "Property '" . implode('.', $path);
}
