<?php

namespace Differ\Formatters\Plain;

use function Differ\Formatters\Stylish\toString;

function plain(array $diff, array $path = []): string
{
    $result = array_reduce($diff, function ($acc, $item) use ($path) {

        $name = $item['name'];
        $status = $item['status'];
        $hasChildren = isset($item['children']) ? true : false;
        $path[] = $name;

        if ($hasChildren) {
            $children = $item['children'];
            $acc[] = plain($children, $path);
        } else {
            $path = getFormattedPath($path);
            if ($status === 'updated') {
                $oldValue = getFormattedValue($item['oldValue']);
                $newValue = getFormattedValue($item['newValue']);
                $acc[] = "{$path}' was updated. From {$oldValue} to {$newValue}";
            } else {
                $value = getFormattedValue($item['value']);
                if ($status === 'added') {
                    $acc[] = "{$path}' was added with value: {$value}";
                } elseif ($status === 'removed') {
                    $acc[] = "{$path}' was removed";
                }
            }
        }

        return $acc;
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
