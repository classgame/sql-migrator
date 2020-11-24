<?php

namespace SqlMigrator;

use SqlMigrator\DB\IExecutor;
use SqlMigrator\DB\IMigrationRepository;
use SqlMigrator\DirectoryMap\Mapper;
use SqlMigrator\Script\Script;
use SqlMigrator\Script\ScriptSelector;

class Migrator
{
    private string $migrationPath;
    private Mapper $mapper;
    private ScriptSelector $scriptSelector;
    private IExecutor $executor;
    private IMigrationRepository $repository;

    public function __construct(
        string $migrationPath,
        IExecutor $executor,
        IMigrationRepository $repository
    ) {
        $this->migrationPath = $migrationPath;
        $this->mapper = new Mapper();
        $this->scriptSelector = new ScriptSelector();
        $this->executor = $executor;
        $this->repository = $repository;
    }

    public function migrate(
        ?string $toVersion = null
    ): void {
        $scripts = $this->getPendingScripts();

        foreach ($scripts as $script) {
            $this->exec($script);
        }
    }

    private function exec(Script $script): void
    {
        $this->saveMigration($script);
        // TODO: Save historic
        $this->executor->exec($script);
    }

    private function saveMigration(Script $script)
    {
        $data = [
            'file_name' => $script->getName(),
            'file_path' => $script->getRelativePath(),
            'version_id' => $script->getVersion(),
        ];

        dd($data);

//        - id
//        - file_name
//        - file_path
//        - version_id
//        - status_id
//        - number_of_lines
//        - number_of_commands
//        - created_at
//        - updated_at

        $this->repository->save($data);
    }

    /**
     * @return Script[]
     */
    private function getPendingScripts(): array
    {
        $executedList = $this->repository->getExecutedList();
        $dirMap = $this->mapper->mapper($this->migrationPath);
        $pendingList = $dirMap->getPendingList($executedList);
        return $this->scriptSelector->resolveScripts($pendingList);
    }
}
