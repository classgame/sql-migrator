<?php

namespace SqlMigrator\DB;

use SqlMigrator\Statement\Script;

class SQLExecutor
{
    private $conn;

    public function __construct(ConnectionCreator $connCreator)
    {
        $this->conn = $connCreator->create();
    }

    public function exec(Script $script): void
    {
        foreach ($script->getStatements() as $statement) {
            $sql = $statement->getCommand();
            $this->execSql($sql);
        }
    }

    public function execSql(string $sql): void
    {
        $stmt = $this->conn->prepare($sql);
//        $success = $stmt->execute();

        dd(mysqlerr($this->conn));

        if (!$success) {
            dd('fail');
        }
    }
}
