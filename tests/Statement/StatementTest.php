<?php

namespace Tests\Statement;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Statement\Statement;

class StatementTest extends TestCase
{
    public function testShouldCreateStatement()
    {
        $command = 'insert into ...';
        $startLine = 1;
        $startSpace = 0;
        $endLine = 4;
        $endSpace = 20;

        $stmt = new Statement(
            $command,
            $startLine,
            $endLine,
            $startSpace,
            $endSpace
        );

        $this->assertEquals($command, $stmt->getCommand());
        $this->assertEquals($startLine, $stmt->getStartLine());
        $this->assertEquals($endLine, $stmt->getEndLine());
        $this->assertEquals($startSpace, $stmt->getStartSpace());
        $this->assertEquals($endSpace, $stmt->getEndSpace());
    }
}
