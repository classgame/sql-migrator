<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\IExecutor;
use SqlMigrator\DB\SQLiteExecutor;
use SqlMigrator\Migrator;
use Tests\DB\MigrationRepositoryStub;

class MigratorTest extends TestCase
{
    use CreateFile;

    private Migrator $migrator;
    private MigrationRepositoryStub $repository;
    private IExecutor $sqlExecutor;

    protected function setUp(): void
    {
        parent::setUp();
        $migrationsPath = root_path('tests/migrations');
        $this->repository = new MigrationRepositoryStub();
        $this->sqlExecutor = new SQLiteExecutor();
        $this->migrator = new Migrator(
            $migrationsPath,
            $this->sqlExecutor,
            $this->repository
        );
    }

    public function testShouldExecuteAllScripts(): void
    {
        $this->mockExecutedList([
            '/v1/v1.0/20201108171115.sql',
            '/v1/v1.0.1/invalid_script.sql',
            '/v1/v2.0/20201108171305.sql'
        ]);

        $this->migrator->migrate();
        $this->assertCount(3, $this->getUsers());
    }

    private function getUsers(): array
    {
        $query = "select * from user";
        return $this->sqlExecutor->execQuery($query);
    }

    private function mockExecutedList(array $executedList): void
    {
        foreach ($executedList as $item) {
            $this->repository->save(['relative_path' => $item]);
        }
    }
}
