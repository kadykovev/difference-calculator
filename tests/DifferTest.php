<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode(DIRECTORY_SEPARATOR, $parts));
    }

    public static function dataProvider(): array
    {
        return [
            ['stylish', 'plainStylishExpected.txt', 'plain'],
            ['plain', 'plainPlainExpected.txt', 'plain'],
            ['json', 'plainJSONExpected.txt', 'plain'],
            ['stylish', 'recursiveStylishExpected.txt', 'recursive'],
            ['plain', 'recursivePlainExpected.txt', 'recursive'],
            ['json', 'recursiveJSONExpected.txt', 'recursive'],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testPlainGenDiff(string $format, string $expectedFile, string $nested): void
    {
        $jsonFile1 = $this->getFixtureFullPath("{$nested}File1.json");
        $jsonFile2 = $this->getFixtureFullPath("{$nested}File2.json");
        $yamlFile1 = $this->getFixtureFullPath("{$nested}File1.yml");
        $yamlFile2 = $this->getFixtureFullPath("{$nested}File2.yml");

        $expected = file_get_contents($this->getFixtureFullPath($expectedFile));
        $this->assertEquals(genDiff($jsonFile1, $jsonFile2, $format), $expected);
        $this->assertEquals(genDiff($yamlFile1, $yamlFile2, $format), $expected);
    }
}
