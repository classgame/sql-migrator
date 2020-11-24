<?php

namespace SqlMigrator\DB;

use SQLite3;
use SqlMigrator\Exception\QueryExecutionException;
use SqlMigrator\Exception\StatementExecutionException;
use SqlMigrator\Script\Script;
use SqlMigrator\Script\Statement;

class SQLiteExecutor implements IExecutor
{
    private SQLite3 $db;

    public function __construct()
    {
        $this->initConnection();
    }

    public function exec(Script $script): void
    {
        $path = $script->getPath();

        foreach ($script->getStatements() as $statement) {
            $this->execStatement($statement, $path);
        }
    }

    public function execStatement(
        Statement $statement,
        string $scriptPath
    ): void {
        $sql = $statement->getCommand();

        try {
            $this->db->exec($sql);
        } catch (\Exception $e) {
            throw new StatementExecutionException(
                $statement,
                $scriptPath,
                $e->getMessage()
            );
        }
    }

    public function execQuery(
        string $query
    ): array {
        try {
            $r = $this->db->query($query);
            $data = [];

            while ($row = $r->fetchArray(SQLITE3_ASSOC))
            {
                $data[] = $row;
            }

            return $data;
        } catch (\Exception $e) {
            throw new QueryExecutionException(
                $query,
                $e->getMessage()
            );
        }
    }

    public function initConnection(): void
    {
        $this->db = new SQLite3(':memory:');
    }
}
