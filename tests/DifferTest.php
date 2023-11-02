<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $plainJSONFile1 = "tests/fixtures/plainFile1.json";
        $plainJSONFile2 = "tests/fixtures/plainFile2.json";

        $plainYAMLFile1 = "tests/fixtures/plainFile1.yml";
        $plainYAMLFile2 = "tests/fixtures/plainFile2.yml";

        $recursiveJSONFile1 = "tests/fixtures/recursiveFile1.json";
        $recursiveJSONFile2 = "tests/fixtures/recursiveFile2.json";

        $recursiveYAMLFile1 = "tests/fixtures/recursiveFile1.yml";
        $recursiveYAMLFile2 = "tests/fixtures/recursiveFile2.yml";

        $plainStylishExpected = file_get_contents(__DIR__ . "/fixtures/plainStylishExpected.txt");
        $recursiveStylishExpected = file_get_contents(__DIR__ . "/fixtures/recursiveStylishExpected.txt");

        $actualStylishPlainJSON = genDiff($plainJSONFile1, $plainJSONFile2);
        $this->assertEquals($plainStylishExpected, $actualStylishPlainJSON);

        $actualStylishPlainYAML = genDiff($plainYAMLFile1, $plainYAMLFile2);
        $this->assertEquals($plainStylishExpected, $actualStylishPlainYAML);

        $actualRecursiveStylishSON = genDiff($recursiveJSONFile1, $recursiveJSONFile2);
        $this->assertEquals($recursiveStylishExpected, $actualRecursiveStylishSON);

        $actualRecursiveStylishYAML = genDiff($recursiveYAMLFile1, $recursiveYAMLFile2);
        $this->assertEquals($recursiveStylishExpected, $actualRecursiveStylishYAML);

        $plainPlainExpected = file_get_contents(__DIR__ . "/fixtures/plainPlainExpected.txt");
        $recursivePlainExpected = file_get_contents(__DIR__ . "/fixtures/recursivePlainExpected.txt");

        $actualPlainPlainJSON = genDiff($plainJSONFile1, $plainJSONFile2, 'plain');
        $this->assertEquals($plainPlainExpected, $actualPlainPlainJSON);

        $actualPlainPlainYAML = genDiff($plainYAMLFile1, $plainYAMLFile2, 'plain');
        $this->assertEquals($plainPlainExpected, $actualPlainPlainYAML);

        $actualReursivePlainJSON = genDiff($recursiveJSONFile1, $recursiveJSONFile2, 'plain');
        $this->assertEquals($recursivePlainExpected, $actualReursivePlainJSON);

        $actualReursivePlainYAML = genDiff($recursiveYAMLFile1, $recursiveYAMLFile2, 'plain');
        $this->assertEquals($recursivePlainExpected, $actualReursivePlainYAML);

        $plainJSONExpected = file_get_contents(__DIR__ . "/fixtures/plainJSONExpected.txt");
        $recursiveJSONExpected = file_get_contents(__DIR__ . "/fixtures/recursiveJSONExpected.txt");

        $actualPlainJSONJSON = genDiff($plainJSONFile1, $plainJSONFile2, 'json');
        $this->assertEquals($plainJSONExpected, $actualPlainJSONJSON);

        $actualPlainJSONYAML = genDiff($plainYAMLFile1, $plainYAMLFile2, 'json');
        $this->assertEquals($plainJSONExpected, $actualPlainJSONYAML);

        $actualReursiveJSONJSON = genDiff($recursiveJSONFile1, $recursiveJSONFile2, 'json');
        $this->assertEquals($recursiveJSONExpected, $actualReursiveJSONJSON);

        $actualReursiveJSONYAML = genDiff($recursiveYAMLFile1, $recursiveYAMLFile2, 'json');
        $this->assertEquals($recursiveJSONExpected, $actualReursiveJSONYAML);
    }
}
