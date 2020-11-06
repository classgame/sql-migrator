<?php

namespace SqlMigrator\Exception;

use SqlMigrator\Script\Statement;
use Throwable;

class StatementExecutionException extends \Exception
{
    public function __construct(
        Statement $statement,
        $scriptPath = '',
        $error = '',
        ?Throwable $previous = null
    ) {
        $message = $this->makeMsg($statement, $scriptPath, $error);
        parent::__construct($message, 0, $previous);
    }

    private function makeMsg(
        Statement $statement,
        string $scriptPath = '',
        string $error = ''
    ): string {
        $info = [
            'error' => $error,
            'script' => $scriptPath,
            'line' => $statement->getStartLine(),
            'position' => $statement->getStartPosition(),
            'command' => $statement->getCommand(),
        ];

        $options = JSON_THROW_ON_ERROR |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES;

        return json_encode($info, $options);
    }
}
