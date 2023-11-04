<?php

namespace Differ\Formatters\Plain;

use function Differ\Formatters\Stylish\toString;

function plain(array $diff, array $steps = []): string
{
    $result = array_reduce($diff, function ($acc, $item) use ($steps) {

        $name = $item['name'];
        $status = $item['status'];
        $hasChildren = isset($item['children']) ? true : false;
        $path = array_merge($steps, [$name]);

        if ($hasChildren) {
            $children = $item['children'];
            $line = plain($children, $path);
        } else {
            $formattedPath = getFormattedPath($path);
            if ($status === 'updated') {
                $oldValue = getFormattedValue($item['oldValue']);
                $newValue = getFormattedValue($item['newValue']);
                $line = "{$formattedPath}' was updated. From {$oldValue} to {$newValue}";
            } else {
                $value = getFormattedValue($item['value']);
                if ($status === 'added') {
                    $line = "{$formattedPath}' was added with value: {$value}";
                } elseif ($status === 'removed') {
                    $line = "{$formattedPath}' was removed";
                }
            }
        }

        return isset($line) ? array_merge($acc, [$line]) : $acc;
    }, []);

    return implode(PHP_EOL, $result);
}

function getFormattedValue(mixed $value): string
{
    $type = gettype($value);

    if ($type === 'object' || $type === 'array') {
        return '[complex value]';
    } elseif ($type === 'string') {
        return sprintf("'%s'", toString($value));
    } else {
        return toString($value);
    }
}

function getFormattedPath(array $path): string
{
    return sprintf("Property '%s", implode('.', $path));
}
