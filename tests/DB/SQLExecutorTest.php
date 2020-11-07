<?php

namespace Tests\DB;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\ConnectionCreator;
use SqlMigrator\DB\SQLExecutor;
use SqlMigrator\Exception\StatementExecutionException;
use SqlMigrator\DirectoryMap\File;
use SqlMigrator\Script\ScriptPreparer;
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

    public function testShouldExecuteScriptStatements(): void
    {
        $fileName = 'script.sql';
        $name1 = 'Usuário 100';
        $name2 = 'Usuário 200';

        $content = "
            insert into user (id, name) values (null, '$name1');
            insert into user (id, name) values (null, '$name2');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $file = new File($filePath, $fileName);
        $script = $preparer->prepare($file);

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

    public function testShouldThrowsExceptionWhenInvalidSqlStatement(): void
    {
        $fileName = 'script.sql';
        $name1 = 'Usuário 100';
        $name2 = 'Usuário 200';

        $content = "
            insert into user (id, name) values (null, '$name1');
            invalid sql;
            insert into user (id, name) values (null, '$name2');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $file = new File($filePath, $fileName);
        $script = $preparer->prepare($file);
        $creator = new ConnectionCreator();
        $executor = new SQLExecutor($creator);

        $msg = '{"error":"You have an error in your SQL syntax; ' .
            'check the manual that corresponds to your MySQL server ' .
            'version for the right syntax to use near \'invalid sql\' at' .
            ' line 1","script":"' . $filePath . '","line":3,' .
            '"position":13,"command":"invalid sql;"}';

        $this->expectException(StatementExecutionException::class);
        $this->expectExceptionMessage($msg);

        $executor->exec($script);

        $this->assertNotNull($script);

        $statements = $script->getStatements();

        $this->assertCount(2, $statements);

        $user1 = $this->getUser($name1);
        $user2 = $this->getUser($name2);

        $this->assertNotNull($user1);
        $this->assertNull($user2);
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
