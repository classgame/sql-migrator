<?php

namespace Tests\Statement;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Statement\ScriptPreparer;
use SqlMigrator\Statement\Statement;

class ScriptPreparerTest extends TestCase
{
    public function testShouldReturnPreparedScript(): void
    {
        $filePath = $this->createFile();
        $preparer = new ScriptPreparer();
        $script = $preparer->prepare($filePath);

        $this->assertNotNull($script);
    }

    public function testShouldReturnPreparedScriptWithStatements(): void
    {
        $content = "
        insert into user values ('Usuário 4');insert into user values ('Usuário 1');insert into user 
        
        values ('Usuário 4');
        ";

        $filePath = $this->createFile($content);
        $preparer = new ScriptPreparer();
        $script = $preparer->prepare($filePath);

        $this->assertNotNull($script);

        $statements = $script->getStatements();

        $this->assertCount(3, $statements);
        $this->assertContainsOnlyInstancesOf(Statement::class, $statements);
    }

    public function createFile(string $content = ''): string
    {
        $path = '/tmp/' . uniqid('', true) . '.sql';
        file_put_contents($path, $content);
        return $path;
    }
}
