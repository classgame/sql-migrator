<?php

namespace SqlMigrator\DB;

use SqlMigrator\Script\Script;

interface IExecutor
{
    public function exec(Script $script): void;
}
