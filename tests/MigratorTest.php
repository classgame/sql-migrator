<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\SQLiteExecutor;
use SqlMigrator\Migrator;
use Tests\DB\MigrationRepositoryStub;

class MigratorTest extends TestCase
{
    use CreateFile;

    private Migrator $migrator;
    private MigrationRepositoryStub $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $migrationsPath = root_path('tests/migrations');
        $this->repository = new MigrationRepositoryStub();
        $executor = new SQLiteExecutor();
        $this->migrator = new Migrator(
            $migrationsPath,
            $executor,
            $this->repository
        );
    }

    public function testShouldExecuteAllScripts(): void
    {
        $this->mockExecutedList([
            '/v1/v1.0/20201108171110.sql',
            '/v1/v1.0.p/v1.0.1/20201108171710.sql'
        ]);
        $this->migrator->migrate();
    }

    private function mockExecutedList(array $executedList): void
    {
        foreach ($executedList as $item) {
            $this->repository->save(['relative_path' => $item]);
        }
    }
}
