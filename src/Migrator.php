<?php

namespace SqlMigrator;

use SqlMigrator\DB\IExecutor;
use SqlMigrator\DB\IMigrationRepository;
use SqlMigrator\DirectoryMap\Mapper;
use SqlMigrator\Script\ScriptSelector;

class Migrator
{
    private Mapper $mapper;
    private ScriptSelector $scriptSelector;
    private IExecutor $executor;
    private IMigrationRepository $repository;

    public function __construct(
        IExecutor $executor,
        IMigrationRepository $repository
    ) {
        $this->mapper = new Mapper();
        $this->scriptSelector = new ScriptSelector();
        $this->executor = $executor;
        $this->repository = $repository;
    }

    public function migrate(
        string $migrationPath,
        ?string $toVersion = null
    ): void {
        // TODO: Consultar ultimo script executado
        // TODO: Obter intervalo de scripts não executados desde o ultimo até a versão destino ou o mais atual
        // TODO: Fazer loop e gerar registro de execução para cada script executado

        $executedList = $this->repository->getExecutedMigrations();
        $dirMap = $this->mapper->mapper($migrationPath);

        $executedList = ['/v1/v1.0/20201108171110.sql', '/v1/v1.0.p/v1.0.1/20201108171710.sql'];
        $pendingList = $dirMap->getPendingScripts($executedList);

        dd($pendingList);

        $scripts = $this->scriptSelector->selectScripts($dirMap);

        foreach ($scripts as $script) {
            $this->executor->exec($script);
        }
    }
}
