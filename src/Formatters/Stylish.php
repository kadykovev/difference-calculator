<?php

namespace Differ\Formatters\Stylish;

use function Functional\reduce_left;

function stylish(array $diff, int $count = 0): string
{
    $result = array_reduce($diff, function ($acc, $item) use ($count) {

        $name = $item['name'];
        $status = $item['status'] ?? 'unchanged';
        $hasChildren = isset($item['children']) ? true : false;

        if ($hasChildren) {
            $children = $item['children'];
            $acc .= buildBefore($count + 1, $status) . $name . ": " . stylish($children, $count + 1);
        } else {
            if ($status === 'updated') {
                $oldValue = getFormattedValue($item['oldValue'], $count + 1);
                $newValue = getFormattedValue($item['newValue'], $count + 1);
                $acc .= buildBefore($count + 1, 'removed') . $name . ': ' . $oldValue . PHP_EOL;
                $acc .= buildBefore($count + 1, 'added') . $name . ': ' . $newValue . PHP_EOL;
            } else {
                $value = getFormattedValue($item['value'], $count + 1);
                $acc .= buildBefore($count + 1, $status) . $name . ': ' . $value . PHP_EOL;
            }
        }

        return $acc;
    });

    return "{" . PHP_EOL . $result . buildBefore($count) . "}" . PHP_EOL;
}

function getFormattedValue(mixed $value, int $count): string
{
    $type = gettype($value);
    $formattedValue = '';

    if ($type === 'object') {
        $formattedValue = getFormattedObject($value, $count);
    } elseif ($type === 'array') {
        $formattedValue = getFormattedArray($value, $count);
    } else {
        $formattedValue = toString($value);
    }

    return $formattedValue;
}

function getFormattedObject(object $obj, int $count): string
{
    $result = reduce_left((array) $obj, function ($value, $key, $collection, $acc) use ($count) {
        $acc .= buildBefore($count + 1) . $key . ": " . getFormattedValue($value, $count + 1) . PHP_EOL;
        return $acc;
    });

    return "{" . PHP_EOL . $result .  buildBefore($count) . "}";
}

function getFormattedArray(array $arr, int $count): string
{
    $result = array_reduce($arr, function ($acc, $item) use ($count) {
        $acc .= buildBefore($count + 1) . toString($item) . PHP_EOL;
        return $acc;
    });

    return "[" . PHP_EOL . $result .  buildBefore($count) . "]";
}

function buildBefore(int $count, string $status = 'unchanged', string $replacer = ' ', int $spacesCount = 4): string
{
    $before = '';

    if ($status === 'nested' || $status === 'unchanged') {
        $before = str_repeat($replacer, $spacesCount * $count);
    } elseif ($status === 'added') {
        $before = str_repeat($replacer, ($spacesCount * $count) - 2) . '+ ';
    } elseif ($status = 'removed') {
        $before = str_repeat($replacer, ($spacesCount * $count) - 2) . '- ';
    }

    return $before;
}

function toString(mixed $value): string
{
     $value = trim(var_export($value, true), "'");

     return $value === 'NULL' ? 'null' : $value;
}
