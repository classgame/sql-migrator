<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\MySQLConn;
use SqlMigrator\DB\SQLiteExecutor;
use SqlMigrator\Migrator;
use Tests\DB\MigrationRepositoryStub;

class MigratorTest extends TestCase
{
    use CreateFile;

    private Migrator $migrator;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = new MigrationRepositoryStub();
        $executor = new SQLiteExecutor();
        $this->migrator = new Migrator($executor, $repository);
    }

    public function testShouldExecuteAllScripts(): void
    {
        $migrationsPath = root_path('tests/migrations');
        $this->migrator->migrate($migrationsPath);
        dd('test');
    }
}
