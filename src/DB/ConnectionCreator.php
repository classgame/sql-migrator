<?php

namespace SqlMigrator\DB;

class ConnectionCreator
{
    /**
     * @return false|\mysqli
     */
    public function create()
    {
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        return mysqli_connect(
            $host,
            $username,
            $password,
            $database
        );
    }
}
