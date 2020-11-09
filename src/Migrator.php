<?php

namespace SqlMigrator;

use SqlMigrator\DB\IExecutor;
use SqlMigrator\DirectoryMap\Mapper;
use SqlMigrator\Script\ScriptSelector;

class Migrator
{
    private Mapper $mapper;
    private ScriptSelector $scriptSelector;
    private IExecutor $executor;

    public function __construct(IExecutor $sqlExecutor)
    {
        $this->mapper = new Mapper();
        $this->scriptSelector = new ScriptSelector();
        $this->executor = $sqlExecutor;
    }

    public function migrate(string $migrationsPath): void
    {
        $dirMap = $this->mapper->mapper($migrationsPath);
        $scripts = $this->scriptSelector->selectScripts($dirMap);

        foreach ($scripts as $script) {
            $this->executor->exec($script);
        }
    }
}
