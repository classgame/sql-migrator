<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\ConnectionCreator;
use SqlMigrator\DB\SQLExecutor;
use SqlMigrator\Migrator;

class MigratorTest extends TestCase
{
    use CreateFile;

    private $conn;

    protected function setUp(): void
    {
        parent::setUp();

        $creator = new ConnectionCreator();
        $this->conn = $creator->create();
        $this->conn->begin_transaction();
    }

    public function testShouldExecuteAllScripts(): void
    {
        $migrationsPath = root_path('tests/migrations');

        $creator = new ConnectionCreator();
        $sqlExecutorMock = new SQLExecutor($creator);
        $migrator = new Migrator($sqlExecutorMock);
        $migrator->migrate($migrationsPath);
    }

    private function getCustomers(): array
    {
        $query = "select * from customer";
        $result = $this->conn->query($query);

        return $result->fetch_all();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->conn->rollback();
        $this->conn->close();
    }
}
