<?php

namespace Tests\Script;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Finder\Finder;
use SqlMigrator\Script\ScriptSelector;

class ScriptSelectorTest extends TestCase
{
    public function testShouldReturnAllScriptsFromDir(): void
    {
        $path = __DIR__ . '/../Finder/migrations';

        $finder = new Finder();
        $scriptSelector = new ScriptSelector();

        $dir = $finder->find($path);
        $scripts = $scriptSelector->selectScripts($dir);

        $this->assertCount(9, $scripts);

        $this->assertEquals('20201105225109.sql', $scripts[0]->getName());
        $this->assertEquals('20201105225118.sql', $scripts[8]->getName());
    }
}
