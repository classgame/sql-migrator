<?php

namespace Tests\Finder;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Finder\Directory;
use SqlMigrator\Finder\Finder;

class FinderTest extends TestCase
{
    public function testShouldFindScriptsInMigrationDirectory(): void
    {
        $migrationPath = __DIR__ . '/migrations';

        $finder = new Finder();
        $migrations = $finder->find($migrationPath);

        $this->assertMigrationDir($migrationPath, $migrations);
    }

    private function assertMigrationDir(
        string $migrationPath,
        Directory $migrations
    ): void {
        $this->assertEquals('migrations', $migrations->getDirName());
        $this->assertEquals($migrationPath, $migrations->getPath());
        $this->assertEquals(0, $migrations->getFilesCount());
        $this->assertEquals(2, $migrations->getSubDirCount());
        $this->assertEmpty($migrations->getFiles());

        [$v1, $v2] = $migrations->getSubDirectories();

        $this->assertV1($v1, $migrationPath);
        $this->assertV2($v2, $migrationPath);

        [$v1_0, $v1_0_1, $v1_1] = $v1->getSubDirectories();

        $this->assertV10($v1_0, $migrationPath);
        $this->assertV101($v1_0_1, $migrationPath);
        $this->assertV11($v1_1, $migrationPath);

        [$v2_0, $v2_0_1] = $v2->getSubDirectories();

        $this->assertV20($v2_0, $migrationPath);
        $this->assertV201($v2_0_1, $migrationPath);
    }

    private function assertV1(Directory $dir, string $migrationPath): void
    {
        $data = [
            'expectedDirName' => 'v1',
            'expectedPath' => $migrationPath . '/v1',
            'expectedCountDirs' => 3,
            'expectedCountFiles' => 0,
            'emptySubDirs' => false,
            'emptyFiles' => true,
        ];

        $this->assertDir($dir, $data);
    }

    private function assertV2(Directory $dir, string $migrationPath): void
    {
        $data = [
            'expectedDirName' => 'v2',
            'expectedPath' => $migrationPath . '/v2',
            'expectedCountDirs' => 2,
            'expectedCountFiles' => 0,
            'emptySubDirs' => false,
            'emptyFiles' => true,
        ];

        $this->assertDir($dir, $data);
    }

    private function assertV10(Directory $dir, string $migrationPath): void
    {
        $data = [
            'expectedDirName' => 'v1.0',
            'expectedPath' => $migrationPath . '/v1/v1.0',
            'expectedCountDirs' => 0,
            'expectedCountFiles' => 3,
            'emptySubDirs' => true,
            'emptyFiles' => false,
        ];

        $this->assertDir($dir, $data);
    }

    private function assertV101(Directory $dir, string $migrationPath): void
    {
        $data = [
            'expectedDirName' => 'v1.0.1',
            'expectedPath' => $migrationPath . '/v1/v1.0.1',
            'expectedCountDirs' => 0,
            'expectedCountFiles' => 1,
            'emptySubDirs' => true,
            'emptyFiles' => false,
        ];

        $this->assertDir($dir, $data);
    }

    private function assertV11(Directory $dir, string $migrationPath): void
    {
        $data = [
            'expectedDirName' => 'v1.1',
            'expectedPath' => $migrationPath . '/v1/v1.1',
            'expectedCountDirs' => 0,
            'expectedCountFiles' => 2,
            'emptySubDirs' => true,
            'emptyFiles' => false,
        ];

        $this->assertDir($dir, $data);
    }

    private function assertV20(Directory $dir, string $migrationPath): void
    {
        $data = [
            'expectedDirName' => 'v2.0',
            'expectedPath' => $migrationPath . '/v2/v2.0',
            'expectedCountDirs' => 0,
            'expectedCountFiles' => 1,
            'emptySubDirs' => true,
            'emptyFiles' => false,
        ];

        $this->assertDir($dir, $data);
    }

    private function assertV201(Directory $dir, string $migrationPath): void
    {
        $data = [
            'expectedDirName' => 'v2.0.1',
            'expectedPath' => $migrationPath . '/v2/v2.0.1',
            'expectedCountDirs' => 0,
            'expectedCountFiles' => 2,
            'emptySubDirs' => true,
            'emptyFiles' => false,
        ];

        $this->assertDir($dir, $data);
    }

    private function assertDir(Directory $dir, array $data): void
    {
        $this->assertEquals($data['expectedDirName'], $dir->getDirName());
        $this->assertEquals($data['expectedPath'], $dir->getPath());
        $this->assertEquals($data['expectedCountDirs'], $dir->getSubDirCount());
        $this->assertEquals($data['expectedCountFiles'], $dir->getFilesCount());

        if ($data['emptyFiles']) {
            $this->assertEmpty($dir->getFiles());
        } else {
            $this->assertNotEmpty($dir->getFiles());
        }

        if ($data['emptySubDirs']) {
            $this->assertEmpty($dir->getSubDirectories());
        } else {
            $this->assertNotEmpty($dir->getSubDirectories());
        }
    }
}
