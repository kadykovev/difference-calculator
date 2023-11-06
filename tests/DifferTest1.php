<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Differ\Differ\genDiff;

/* Я оставил проверку плоских файлов, потому что понаделал и жалко выбрасывть),
без них тест в 47 строчек и состоит, как вы сказали, из 4 методов, если не нужны то уберу*/

class DifferTest extends TestCase
{
    private $plainJSON1;
    private $plainJSON2;
    private $plainYAML1;
    private $plainYAML2;
    private $recursiveJSON1;
    private $recursiveJSON2;
    private $recursiveYAML1;
    private $recursiveYAML2;

    public function setUp(): void
    {
        $this->plainJSON1 = $this->getFixtureFullPath("plainFile1.json");
        $this->plainJSON2 = $this->getFixtureFullPath("plainFile2.json");
        $this->plainYAML1 = $this->getFixtureFullPath("plainFile1.yml");
        $this->plainYAML2 = $this->getFixtureFullPath("plainFile2.yml");
        $this->recursiveJSON1 = $this->getFixtureFullPath("recursiveFile1.json");
        $this->recursiveJSON2 = $this->getFixtureFullPath("recursiveFile2.json");
        $this->recursiveYAML1 = $this->getFixtureFullPath("recursiveFile1.yml");
        $this->recursiveYAML2 = $this->getFixtureFullPath("recursiveFile2.yml");
    }

    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode(DIRECTORY_SEPARATOR, $parts));
    }

    public static function plainProvider(): array
    {
        return [
            ['stylish', 'plainStylishExpected.txt'],
            ['plain', 'plainPlainExpected.txt'],
            ['json', 'plainJSONExpected.txt'],
        ];
    }

    public static function recursiveProvider(): array
    {
        return [
            ['stylish', 'recursiveStylishExpected.txt'],
            ['plain', 'recursivePlainExpected.txt'],
            ['json', 'recursiveJSONExpected.txt'],
        ];
    }

    #[DataProvider('plainProvider')]
    public function testPlainGenDiff(string $format, string $expectedFile): void
    {
        $expected = file_get_contents($this->getFixtureFullPath($expectedFile));
        $this->assertEquals(genDiff($this->plainJSON1, $this->plainJSON2, $format), $expected);
        $this->assertEquals(genDiff($this->plainYAML1, $this->plainYAML2, $format), $expected);
    }

    #[DataProvider('recursiveProvider')]
    public function testRecursiveGenDiff(string $format, string $expectedFile): void
    {
        $expected = file_get_contents($this->getFixtureFullPath($expectedFile));
        $this->assertEquals(genDiff($this->recursiveJSON1, $this->recursiveJSON2, $format), $expected);
        $this->assertEquals(genDiff($this->recursiveYAML1, $this->recursiveYAML2, $format), $expected);
    }
}
