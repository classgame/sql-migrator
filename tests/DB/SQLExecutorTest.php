<?php

namespace Tests\DB;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DB\ConnectionCreator;
use SqlMigrator\DB\SQLExecutor;
use SqlMigrator\Statement\ScriptPreparer;
use SqlMigrator\Statement\Statement;

class SQLExecutorTest extends TestCase
{
    public function test(): void
    {
        self::markTestSkipped();
        $content = "
            insert into user (id, name) values (null, 'Usuário 2');
            insert into user (id, name) values (null, 'Usuário 4');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $script = $preparer->prepare($filePath);

        $creator = new ConnectionCreator();
        $executor = new SQLExecutor($creator);
        $executor->exec($script);

        $this->assertNotNull($script);

        $statements = $script->getStatements();

        $this->assertCount(3, $statements);
        $this->assertContainsOnlyInstancesOf(Statement::class, $statements);
    }
}
