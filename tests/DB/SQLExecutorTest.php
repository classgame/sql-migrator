<?php

namespace Tests\DB;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\ConnectionCreator;
use SqlMigrator\DB\SQLExecutor;
use SqlMigrator\Statement\ScriptPreparer;
use Tests\CreateFile;

class SQLExecutorTest extends TestCase
{
    private $conn;
    use CreateFile;

    protected function setUp(): void
    {
        parent::setUp();

        $creator = new ConnectionCreator();
        $this->conn = $creator->create();
        $this->conn->begin_transaction();
    }

    public function test(): void
    {
        $name1 = 'Usuário 100';
        $name2 = 'Usuário 200';

        $content = "
            insert into user (id, name) values (null, '$name1');
            insert into user (id, name) values (null, '$name2');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $script = $preparer->prepare($filePath);

        $creator = new ConnectionCreator();
        $executor = new SQLExecutor($creator);
        $executor->exec($script);

        $this->assertNotNull($script);

        $statements = $script->getStatements();

        $this->assertCount(2, $statements);

        $user1 = $this->getUser($name1);
        $user2 = $this->getUser($name2);

        $this->assertNotNull($user1);
        $this->assertNotNull($user2);
    }

    private function getUser(string $name): \stdClass
    {
        $query = "select * from user where name = '$name'";
        $result = $this->conn->query($query);

        return $result->fetch_object();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->conn->rollback();
    }
}
