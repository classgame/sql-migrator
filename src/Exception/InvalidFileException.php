<?php

namespace SqlMigrator\Exception;

use Throwable;

class InvalidFileException extends \Exception
{
    public function __construct($path, ?Throwable $previous = null)
    {
        $msg = "'$path' is not a valid file";
        parent::__construct($msg, 0, $previous);
    }
}
