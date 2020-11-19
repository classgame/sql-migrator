<?php

namespace SqlMigrator\Exception;

use Throwable;

class QueryExecutionException extends \Exception
{
    public function __construct(
        string $query,
        $error = '',
        ?Throwable $previous = null
    ) {
        $message = $this->makeMsg($query, $error);
        parent::__construct($message, 0, $previous);
    }

    private function makeMsg(
        string $query,
        string $error = ''
    ): string {
        $info = [
            'error' => $error,
            'query' => $query,
        ];

        $options = JSON_THROW_ON_ERROR |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES;

        return json_encode($info, $options);
    }
}
