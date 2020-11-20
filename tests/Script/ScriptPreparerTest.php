<?php

namespace Tests\Script;

use PHPUnit\Framework\TestCase;
use SqlMigrator\DirectoryMap\MappedFile;
use SqlMigrator\Script\ScriptPreparer;
use SqlMigrator\Script\Statement;

class ScriptPreparerTest extends TestCase
{
    public function testShouldReturnPreparedScript(): void
    {
        $filePath = $this->createFile();
        $fileName = 'script.sql';

        $preparer = new ScriptPreparer();
        $file = new MappedFile($filePath, $fileName);

        $script = $preparer->prepare($file);

        $this->assertNotNull($script);
    }

    public function testShouldReturnPreparedScriptWithStatements(): void
    {
        $content = "
        insert into user values ('Usuário 4');insert into user values ('Usuário 1');insert into user 
        
        values ('Usuário 4');
        ";

        $filePath = $this->createFile($content);
        $fileName = 'script.sql';

        $preparer = new ScriptPreparer();
        $file = new MappedFile($filePath, $fileName);

        $script = $preparer->prepare($file);

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
