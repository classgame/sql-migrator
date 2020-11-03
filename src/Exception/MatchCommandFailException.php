<?php

namespace SqlMigrator\Exception;

use Throwable;

class MatchCommandFailException extends \Exception
{
    public function __construct(string $command, ?Throwable $previous = null)
    {
        $msg = "Fail to try match command metadata for command '$command'";
        parent::__construct($msg, 0, $previous);
    }
}
