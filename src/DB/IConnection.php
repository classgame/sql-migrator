<?php

namespace SqlMigrator\DB;

interface IConnection
{
    /**
     * @return mixed
     */
    public function create();
}