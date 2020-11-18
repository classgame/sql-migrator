<?php

namespace Tests\DB;

use PHPUnit\Framework\TestCase;
use SQLite3;
use SqlMigrator\DB\MySQLConn;
use SqlMigrator\DB\MySQLExecutor;
use SqlMigrator\DB\SQLiteConn;
use SqlMigrator\DB\SQLiteExecutor;
use SqlMigrator\Exception\StatementExecutionException;
use SqlMigrator\DirectoryMap\File;
use SqlMigrator\Script\ScriptPreparer;
use Tests\CreateFile;

class SQLExecutorTest extends TestCase
{
    use CreateFile;

    public function testShouldExecuteScriptStatements(): void
    {
        $fileName = 'script.sql';
        $name1 = 'Usuário 100';
        $name2 = 'Usuário 200';

        $content = "
            CREATE TABLE user (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL
            );

            insert into user (id, name) values (null, '$name1');
            insert into user (id, name) values (null, '$name2');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $file = new File($filePath, $fileName);
        $script = $preparer->prepare($file);

        $creator = new SQLiteConn();
        $executor = new SQLiteExecutor($creator);
        $executor->exec($script);
        dd(1);

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
        $creator = new MySQLConn();
        $executor = new MySQLExecutor($creator);

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
