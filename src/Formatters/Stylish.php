<?php

namespace Differ\Formatters\Stylish;

use function Functional\reduce_left;

function stylish(mixed $diff, int $count = 0, string $type = 'diff'): string
{
    if ($type === 'diff') {
        $result = array_reduce($diff, function ($acc, $item) use ($count) {

            $name = $item['name'];
            $status = $item['status'] ?? 'unchanged';
            $hasChildren = isset($item['children']) ? true : false;

            if ($hasChildren) {
                $acc .= getSpace($count + 1, $status) . $name . ": " . stylish($item['children'], $count + 1);
                return $acc;
            } else {
                if ($status === 'changed') {
                    $oldValue = $item['oldValue'];
                    $newValue = $item['newValue'];
                    $oldValueType = gettype($item['oldValue']);
                    $newValueType = gettype($item['newValue']);
                    $acc .= getSpace($count + 1, 'removed') . $name . ': '
                    . stylish($oldValue, $count + 1, $oldValueType) . "\n";
                    $acc .= getSpace($count + 1, 'added') . $name . ': '
                    . stylish($newValue, $count + 1, $newValueType) . "\n";
                } else {
                    $type = gettype($item['value']);
                    $value = $item['value'];
                    $acc .= getSpace($count + 1, $status) . $name . ': ' . stylish($value, $count + 1, $type)  . "\n";
                    return $acc;
                }

                return $acc;
            }
        });

        return "{\n" . $result . getSpace($count) . "}\n";
    } elseif ($type === 'object') {
        $result = reduce_left((array) $diff, function ($value, $key, $collection, $acc) use ($count) {
            $type = gettype($value);
            $acc .= getSpace($count + 1) . $key . ": " . stylish($value, $count + 1, $type)  . "\n";
            return $acc;
        });
        return "{\n" . $result .  getSpace($count) . "}";
    } elseif ($type === 'array') {
        $result = array_reduce($diff, function ($acc, $item) use ($count) {
            $acc .= getSpace($count + 1) . toString($item) . "\n";
            return $acc;
        });
        return "[\n" . $result .  getSpace($count) . "]";
    } else {
        return toString($diff);
    }
}

function toString(mixed $value): string
{
     return trim(var_export($value, true), "'");
}

function getSpace(int $count, string $status = 'unchanged', string $replacer = ' ', int $spacesCount = 4): string
{
    $space = '';

    switch ($status) {
        case 'unchanged':
            $space = str_repeat($replacer, $spacesCount * $count);
            break;
        case 'added':
            $space = str_repeat($replacer, ($spacesCount * $count) - 2) . '+ ';
            break;
        case 'removed':
            $space = str_repeat($replacer, ($spacesCount * $count) - 2) . '- ';
            break;
    }

    return $space;
}
