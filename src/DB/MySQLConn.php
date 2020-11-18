<?php

namespace SqlMigrator\DB;

class MySQLConn implements IConnection
{
    /**
     * @var \mysqli
     */
    private static $conn;

    /**
     * @return false|\mysqli
     */
    public function create()
    {
        if (self::$conn) {
            return self::$conn;
        }

        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        self::$conn = mysqli_connect(
            $host,
            $username,
            $password,
            $database
        );

        return self::$conn;
    }
}
