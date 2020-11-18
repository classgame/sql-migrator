<?php

namespace SqlMigrator\DB;

use SqlMigrator\Exception\StatementExecutionException;
use SqlMigrator\Script\Script;
use SqlMigrator\Script\Statement;

class SQLiteExecutor implements IExecutor
{
    /**
     * @var \mysqli
     */
    private $db;

    public function __construct(SQLiteConn $creator)
    {
        $this->db = $creator->create();
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
}
