<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/result.txt");
        $actual = genDiff("tests/fixtures/file1.json", "tests/fixtures/file2.json");

        $this->assertEquals($expected, $actual);
    }
}
