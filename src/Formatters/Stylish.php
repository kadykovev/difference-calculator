<?php

namespace Differ\Formatters\Stylish;

use function Functional\reduce_left;

function stylish(mixed $diff, int $count = 0): string
{
    $result = array_reduce($diff, function ($acc, $item) use ($count) {

        $name = $item['name'];
        $status = $item['status'] ?? 'unchanged';
        $hasChildren = isset($item['children']) ? true : false;

        if ($hasChildren) {
            $acc .= buildBefore($count + 1, $status) . $name . ": " . stylish($item['children'], $count + 1);
        } else {
            if ($status === 'updated') {
                $oldValue = $item['oldValue'];
                $newValue = $item['newValue'];
                $acc .= buildBefore($count + 1, 'removed') . $name . ': ' . displayValue($oldValue, $count + 1) . "\n";
                $acc .= buildBefore($count + 1, 'added') . $name . ': ' . displayValue($newValue, $count + 1) . "\n";
            } else {
                $value = $item['value'];
                $acc .= buildBefore($count + 1, $status) . $name . ': ' . displayValue($value, $count + 1)  . "\n";
            }
        }

        return $acc;
    });

    return "{\n" . $result . buildBefore($count) . "}\n";
}

function displayValue(mixed $value, int $count): string
{
    $type = gettype($value);

    if ($type === 'object') {
        return displayObject($value, $count);
    } elseif ($type === 'array') {
        return displayArray($value, $count);
    } else {
        return toString($value);
    }
}

function displayObject(object $obj, int $count): string
{
    $result = reduce_left((array) $obj, function ($value, $key, $collection, $acc) use ($count) {
        $acc .= buildBefore($count + 1) . $key . ": " . displayValue($value, $count + 1)  . "\n";
        return $acc;
    });

    return "{\n" . $result .  buildBefore($count) . "}";
}

function displayArray(array $arr, int $count): string
{
    $result = array_reduce($arr, function ($acc, $item) use ($count) {
        $acc .= buildBefore($count + 1) . toString($item) . "\n";
        return $acc;
    });

    return "[\n" . $result .  buildBefore($count) . "]";
}

function toString(mixed $value): string
{
     $value = trim(var_export($value, true), "'");

     return $value === 'NULL' ? 'null' : $value;
}

function buildBefore(int $count, string $status = 'unchanged', string $replacer = ' ', int $spacesCount = 4): string
{
    $before = '';

    switch ($status) {
        case 'unchanged':
            $before = str_repeat($replacer, $spacesCount * $count);
            break;
        case 'added':
            $before = str_repeat($replacer, ($spacesCount * $count) - 2) . '+ ';
            break;
        case 'removed':
            $before = str_repeat($replacer, ($spacesCount * $count) - 2) . '- ';
            break;
    }

    return $before;
}
