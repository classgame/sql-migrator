<?php

namespace Tests\Script;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Exception\InvalidFileException;
use SqlMigrator\Script\Script;
use SqlMigrator\Script\ScriptPreparer;

class ScriptTest extends TestCase
{
    public function testGetPath(): void
    {
        $path = $this->createFile();
        $script = new Script($path);

        $this->assertEquals($path, $script->getPath());
    }

    public function testShouldThrowsExceptionWhenInvalidScriptFile(): void
    {
        $filePath = 'invalid_file';
        $preparer = new ScriptPreparer();

        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessage("'invalid_file' is not a valid file");

        $preparer->prepare($filePath);
    }

    public function testGetNumberOfLinesGreaterThanOne(): void
    {
        $content = 'line 1
        line2';

        $path = $this->createFile($content);
        $script = new Script($path);
        $quantity = $script->getLineQuantity();

        $this->assertEquals(2, $quantity);
    }

    public function testGetNumberOfLinesEqualsZero(): void
    {
        $content = '';

        $path = $this->createFile($content);
        $script = new Script($path);
        $quantity = $script->getLineQuantity();

        $this->assertEquals(0, $quantity);
    }

    public function createFile(string $content = ''): string
    {
        $path = '/tmp/' . uniqid('', true) . '.sql';
        file_put_contents($path, $content);
        return $path;
    }
}
