<?php

namespace SqlMigrator\DB;

use SqlMigrator\Exception\StatementExecutionException;
use SqlMigrator\Script\Script;
use SqlMigrator\Script\Statement;

class MySQLExecutor implements IExecutor
{
    private \mysqli $conn;

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

    public function initConnection(): void
    {
        if (!$this->conn) {
            $host = env('DB_HOST');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $database = env('DB_DATABASE');

            $this->conn = mysqli_connect(
                $host,
                $username,
                $password,
                $database
            );
        }
    }
}
