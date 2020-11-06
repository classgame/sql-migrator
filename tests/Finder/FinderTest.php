<?php

namespace Tests\Finder;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Finder\Finder;

class FinderTest extends TestCase
{
    public function testShouldFindScriptsInMigrationDirectory(): void
    {
        $migrationPath = __DIR__ . '/migrations';

        $finder = new Finder();
        $migrations = $finder->find($migrationPath);

        $this->assertEquals('migrations', $migrations->getDirName());
        $this->assertEquals($migrationPath, $migrations->getPath());
        $this->assertEquals(0, $migrations->getFilesCount());
        $this->assertEquals(2, $migrations->getSubDirCount());
        $this->assertEmpty($migrations->getFiles());

        [$v1, $v2] = $migrations->getSubDirectories();

        $v1Path = $migrationPath . '/v1';
        $v2Path = $migrationPath . '/v2';

        $this->assertEquals('v1', $v1->getDirName());
        $this->assertEquals($v1Path, $v1->getPath());
        $this->assertEquals(3, $v1->getSubDirCount());
        $this->assertEquals(0, $v1->getFilesCount());
        $this->assertEmpty($v1->getFiles());

        [$v1_0, $v1_0_1, $v1_1] = $v1->getSubDirectories();

        $v1_0Path = $v1Path . '/v1.0';

        $this->assertEquals('v1.0', $v1_0->getDirName());
        $this->assertEquals($v1_0Path, $v1_0->getPath());
        $this->assertEquals(0, $v1_0->getSubDirCount());
        $this->assertEquals(3, $v1_0->getFilesCount());
        $this->assertEmpty($v1_0->getSubDirectories());

        $this->assertEquals('v2', $v2->getDirName());
        $this->assertEquals($v2Path, $v2->getPath());
        $this->assertEquals(2, $v2->getSubDirCount());
        $this->assertEquals(0, $v2->getFilesCount());
        $this->assertEmpty($v2->getFiles());
    }
}
