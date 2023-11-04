<?php

namespace Differ\Formatters\Stylish;

function stylish(array $diff): string
{
    $stylish = function (array $diff, int $count = 0) use (&$stylish) {

        $result = array_map(function ($item) use ($count, $stylish) {

            $name = $item['name'];
            $status = $item['status'];
            $hasChildren = isset($item['children']) ? true : false;

            if ($hasChildren) {
                $children = $item['children'];
                $beforeName = buildBefore($count + 1, $status);
                $beforeBracket = buildBefore($count + 1);
                $internalLines = $stylish($children, $count + 1);
                $lines = sprintf(
                    "%s%s: {%s%s%s%s}",
                    $beforeName,
                    $name,
                    PHP_EOL,
                    $internalLines,
                    PHP_EOL,
                    $beforeBracket
                );
            } elseif ($status === 'updated') {
                $oldValue = getFormattedValue($item['oldValue'], $count + 1);
                $newValue = getFormattedValue($item['newValue'], $count + 1);
                $beforeOldValue = buildBefore($count + 1, 'removed');
                $beforeNewValue = buildBefore($count + 1, 'added');
                $lines = sprintf(
                    "%s%s: %s%s%s%s: %s",
                    $beforeOldValue,
                    $name,
                    $oldValue,
                    PHP_EOL,
                    $beforeNewValue,
                    $name,
                    $newValue
                );
            } else {
                $value = getFormattedValue($item['value'], $count + 1);
                $beforeName = buildBefore($count + 1, $status);
                $lines = sprintf("%s%s: %s", $beforeName, $name, $value);
            }

            return $lines;
        }, $diff);

        return implode(PHP_EOL, $result);
    };

    return sprintf("{%s%s%s}", PHP_EOL, $stylish($diff), PHP_EOL);
}

function getFormattedValue(mixed $value, int $count): string
{
    $type = gettype($value);

    if ($type === 'object') {
        return getFormattedObject($value, $count);
    } elseif ($type === 'array') {
        return getFormattedArray($value, $count);
    } else {
        return toString($value);
    }
}

function getFormattedObject(object $obj, int $count): string
{
    $arr = (array) $obj;
    $beforeBracket = buildBefore($count);

    $stylishObject = function ($arr, $count) {
        $result = array_map(function ($key, $value) use ($count) {
            $beforeKey = buildBefore($count);
            $formattedValue = getFormattedValue($value, $count);
            return sprintf("%s%s: %s", $beforeKey, $key, $formattedValue);
        }, array_keys($arr), $arr);

        return implode(PHP_EOL, $result);
    };

    return sprintf("{%s%s%s%s}", PHP_EOL, $stylishObject($arr, $count + 1), PHP_EOL, $beforeBracket);
}

function getFormattedArray(array $arr, int $count): string
{
    $beforeBracket = buildBefore($count);

    $stylishArray = function ($arr, $count) {
        $result = array_map(function ($value) use ($count) {
            $beforeValue = buildBefore($count);
            $formattedValue = getFormattedValue($value, $count);
            return sprintf("%s%s", $beforeValue, $formattedValue);
        }, $arr);

        return implode(PHP_EOL, $result);
    };

    return sprintf("[%s%s%s%s]", PHP_EOL, $stylishArray($arr, $count + 1), PHP_EOL, $beforeBracket);
}

function buildBefore(int $count, string $status = 'unchanged', string $replacer = ' ', int $spacesCount = 4): string
{
    if ($status === 'nested' || $status === 'unchanged') {
        return str_repeat($replacer, $spacesCount * $count);
    } elseif ($status === 'added') {
        return str_repeat($replacer, ($spacesCount * $count) - 2) . '+ ';
    } elseif ($status === 'removed') {
        return str_repeat($replacer, ($spacesCount * $count) - 2) . '- ';
    }
}

function toString(mixed $value): string
{
    return $value === null ? 'null' : trim(var_export($value, true), "'");
}
