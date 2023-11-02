<?php

namespace Differ\Formatters\Json;

function json(array $diff): string
{
    return (string) json_encode($diff, JSON_PRETTY_PRINT);
}
