<?php

namespace Tests\Script;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Exception\InvalidFileException;
use SqlMigrator\DirectoryMap\File;
use SqlMigrator\Script\Script;
use SqlMigrator\Script\ScriptPreparer;

class ScriptTest extends TestCase
{
    public function testGetPath(): void
    {
        $path = $this->createFile();
        $name = 'name';

        $script = new Script($path, $name);

        $this->assertEquals($path, $script->getPath());
    }

    public function testShouldThrowsExceptionWhenInvalidScriptFile(): void
    {
        $filePath = 'invalid_file';
        $fileName = 'script.sql';

        $preparer = new ScriptPreparer();
        $file = new File($filePath, $fileName);

        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessage("'invalid_file' is not a valid file");

        $preparer->prepare($file);
    }

    public function testGetNumberOfLinesGreaterThanOne(): void
    {
        $name = 'name';
        $content = 'line 1
        line2';

        $path = $this->createFile($content);
        $script = new Script($path, $name);
        $quantity = $script->getLineQuantity();

        $this->assertEquals(2, $quantity);
    }

    public function testGetNumberOfLinesEqualsZero(): void
    {
        $name = 'name';
        $content = '';

        $path = $this->createFile($content);
        $script = new Script($path, $name);
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
