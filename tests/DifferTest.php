<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $plainStylishExpected = file_get_contents(__DIR__ . "/fixtures/plainStylishExpected.txt");

        $plainJSONFile1 = "tests/fixtures/plainFile1.json";
        $plainJSONFile2 = "tests/fixtures/plainFile2.json";
        $actualStylishJSON = genDiff($plainJSONFile1, $plainJSONFile2);
        $this->assertEquals($plainStylishExpected, $actualStylishJSON);

        $plainYAMLFile1 = "tests/fixtures/plainFile1.yml";
        $plainYAMLFile2 = "tests/fixtures/plainFile2.yml";
        $actualStylishYAML = genDiff($plainYAMLFile1, $plainYAMLFile2);
        $this->assertEquals($plainStylishExpected, $actualStylishYAML);

        $recursiveStylishExpected = file_get_contents(__DIR__ . "/fixtures/recursiveStylishExpected.txt");

        $recursiveJSONFile1 = "tests/fixtures/recursiveFile1.json";
        $recursiveJSONFile2 = "tests/fixtures/recursiveFile2.json";
        $actualRecursiveStylishSON = genDiff($recursiveJSONFile1, $recursiveJSONFile2);
        $this->assertEquals($recursiveStylishExpected, $actualRecursiveStylishSON);

        $recursiveYAMLFile1 = "tests/fixtures/recursiveFile1.yml";
        $recursiveYAMLFile2 = "tests/fixtures/recursiveFile2.yml";
        $actualRecursiveStylishYAML = genDiff($recursiveYAMLFile1, $recursiveYAMLFile2);
        $this->assertEquals($recursiveStylishExpected, $actualRecursiveStylishYAML);

        $plainPlainExpected = file_get_contents(__DIR__ . "/fixtures/plainPlainExpected.txt");
        $actualPlainPlainJSON = genDiff($plainJSONFile1, $plainJSONFile2, 'plain');
        $this->assertEquals($plainPlainExpected, $actualPlainPlainJSON);

        $recursivePlainExpected = file_get_contents(__DIR__ . "/fixtures/recursivePlainExpected.txt");
        $actualReursivePlainJSON = genDiff($recursiveJSONFile1, $recursiveJSONFile2, 'plain');
        $this->assertEquals($recursivePlainExpected, $actualReursivePlainJSON);
    }
}
