<?php

namespace Differ\Formatters\Plain;

function plain(array $diff): string
{
/*     $result = array_reduce($diff, function ($acc, $item) {

        $name = $item['name'];
        $status = $item['status'] ?? 'unchanged';
        $hasChildren = isset($item['children']) ? true : false;

        if ($hasChildren) {
            $acc .= ;
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

    return "{\n" . $result . buildBefore($count) . "}\n"; */
}
