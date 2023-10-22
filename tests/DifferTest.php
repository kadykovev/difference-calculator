<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");

        $plainJSONFile1 = "tests/fixtures/file1.json";
        $plainJSONFile2 = "tests/fixtures/file2.json";
        $actualPlainJSON = genDiff($plainJSONFile1, $plainJSONFile2);
        $this->assertEquals($expected, $actualPlainJSON);

        $plainYAMLFile1 = "tests/fixtures/file1.yml";
        $plainYAMLFile2 = "tests/fixtures/file2.yml";
        $actualPlainYAML = genDiff($plainYAMLFile1, $plainYAMLFile2);
        $this->assertEquals($expected, $actualPlainYAML);
    }
}
