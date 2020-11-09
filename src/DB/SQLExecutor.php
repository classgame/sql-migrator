<?php

namespace SqlMigrator\DB;

use SqlMigrator\Exception\StatementExecutionException;
use SqlMigrator\Script\Script;
use SqlMigrator\Script\Statement;

class SQLExecutor implements IExecutor
{
    /**
     * @var \mysqli
     */
    private $conn;

    public function __construct(ConnectionCreator $creator)
    {
        $this->conn = $creator->create();
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
        $prepare = $this->conn->prepare($sql);

        if (!$prepare) {
            throw new StatementExecutionException(
                $statement,
                $scriptPath,
                mysqli_error($this->conn)
            );
        }

        $success = $prepare->execute();

        if (!$success) {
            throw new StatementExecutionException(
                $statement,
                $scriptPath,
                mysqli_error($this->conn)
            );
        }
    }
}
