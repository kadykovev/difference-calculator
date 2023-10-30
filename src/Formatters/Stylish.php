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
            $acc .= getIndentAndSign($count + 1, $status) . $name . ": " . stylish($item['children'], $count + 1);
        } else {
            if ($status === 'changed') {
                $oldValue = $item['oldValue'];
                $newValue = $item['newValue'];
                $acc .= getIndentAndSign($count + 1, 'removed') . $name . ': ' . displayValue($oldValue, $count + 1) . "\n";
                $acc .= getIndentAndSign($count + 1, 'added') . $name . ': ' . displayValue($newValue, $count + 1) . "\n";
            } else {
                $value = $item['value'];
                $acc .= getIndentAndSign($count + 1, $status) . $name . ': ' . displayValue($value, $count + 1)  . "\n";
            }
        }

        return $acc;
    });

    return "{\n" . $result . getIndentAndSign($count) . "}\n";
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
        $acc .= getIndentAndSign($count + 1) . $key . ": " . displayValue($value, $count + 1)  . "\n";
        return $acc;
    });

    return "{\n" . $result .  getIndentAndSign($count) . "}";
}

function displayArray(array $arr, $count): string
{
    $result = array_reduce($arr, function ($acc, $item) use ($count) {
        $acc .= getIndentAndSign($count + 1) . toString($item) . "\n";
        return $acc;
    });

    return "[\n" . $result .  getIndentAndSign($count) . "]";
}

function toString(mixed $value): string
{
     return trim(var_export($value, true), "'");
}

function getIndentAndSign($count, string $status = 'unchanged', string $replacer = ' ', int $spacesCount = 4): string
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
