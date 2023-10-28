<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $plainFilesExpected = file_get_contents(__DIR__ . "/fixtures/plainFilesExpected.txt");

        $plainJSONFile1 = "tests/fixtures/plainFile1.json";
        $plainJSONFile2 = "tests/fixtures/plainFile2.json";
        $actualPlainJSON = genDiff($plainJSONFile1, $plainJSONFile2);
        $this->assertEquals($plainFilesExpected, $actualPlainJSON);

        $plainYAMLFile1 = "tests/fixtures/plainFile1.yml";
        $plainYAMLFile2 = "tests/fixtures/plainFile2.yml";
        $actualPlainYAML = genDiff($plainYAMLFile1, $plainYAMLFile2);
        $this->assertEquals($plainFilesExpected, $actualPlainYAML);

        $recursiveFilesExpected = file_get_contents(__DIR__ . "/fixtures/recursiveFilesExpected.txt");

        $recursiveJSONFile1 = "tests/fixtures/recursiveFile1.json";
        $recursiveJSONFile2 = "tests/fixtures/recursiveFile2.json";
        $actualRecursiveJSON = genDiff($recursiveJSONFile1, $recursiveJSONFile2);
        $this->assertEquals($recursiveFilesExpected, $actualRecursiveJSON);

        $recursiveYAMLFile1 = "tests/fixtures/recursiveFile1.yml";
        $recursiveYAMLFile2 = "tests/fixtures/recursiveFile2.yml";
        $actualRecursiveYAML = genDiff($recursiveYAMLFile1, $recursiveYAMLFile2);
        $this->assertEquals($recursiveFilesExpected, $actualRecursiveYAML);
    }
}
