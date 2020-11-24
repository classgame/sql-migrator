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

    public function getExecutedList(): array
    {
        return array_map(
            fn (array $item) => $item['relative_path'],
            $this->migrations
        );
    }

    public function saveExecHistoric(array $data): void
    {
        $this->historic[] = $data;
    }
}
