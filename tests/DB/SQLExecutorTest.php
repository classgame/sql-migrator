<?php

namespace Tests\DB;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\MySQLConn;
use SqlMigrator\DB\SQLiteConn;
use SqlMigrator\DB\SQLiteExecutor;
use SqlMigrator\Exception\StatementExecutionException;
use SqlMigrator\DirectoryMap\File;
use SqlMigrator\Script\ScriptPreparer;
use Tests\CreateFile;

class SQLExecutorTest extends TestCase
{
    use CreateFile;

    private SQLiteExecutor $sqlExecutor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sqlExecutor = new SQLiteExecutor();
    }

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
            
            insert into user (id, name) 
                values (null, '$name2');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $file = new File($filePath, $fileName);
        $script = $preparer->prepare($file);

        $this->sqlExecutor->exec($script);

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
            CREATE TABLE user (id INTEGER PRIMARY KEY, name TEXT NOT NULL);
            insert into user (id, name) values (null, '$name1');
            invalid sql;
            insert into user (id, name) values (null, '$name2');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $file = new File($filePath, $fileName);
        $script = $preparer->prepare($file);

        $this->expectException(StatementExecutionException::class);

        $this->sqlExecutor->exec($script);

        $user1 = $this->getUser($name1);
        $user2 = $this->getUser($name2);

        $this->assertNotNull($user1);
        $this->assertNull($user2);
    }

    private function getUser(string $name): array
    {
        $query = "select * from user where name = '$name'";
        return $this->sqlExecutor->execQuery($query);
    }
}
