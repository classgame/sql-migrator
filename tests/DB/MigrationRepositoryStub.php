<?php

namespace Tests\DB;

use SqlMigrator\DB\IMigrationRepository;

class MigrationRepositoryStub implements IMigrationRepository
{
    private array $migrations = [];
    private array $historic = [];

    public function save(array $data): void
    {
        $this->migrations[] = $data;
    }

    public function getExecutedMigrations(): array
    {
        return $this->migrations;
    }

    public function saveExecHistoric(array $data): void
    {
        $this->historic[] = $data;
    }
}