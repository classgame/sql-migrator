<?php

namespace SqlMigrator\DB;

interface IMigrationRepository
{
    public function save(array $data): void;

    public function getExecutedMigrations(): array;

    public function saveExecHistoric(array $data): void;
}