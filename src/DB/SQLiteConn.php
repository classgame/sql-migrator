<?php

namespace SqlMigrator\DB;

use SQLite3;

class SQLiteConn implements IConnection
{

    /**
     * @var SQLite3|mixed
     */
    private static $db;

    /**
     * @return SQLite3|mixed
     */
    public function create()
    {
        if (self::$db) {
            return self::$db;
        }

        self::$db = new SQLite3(':memory:');

        return self::$db;
    }
}
