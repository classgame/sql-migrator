<?php

namespace Tests\Statement;

use PHPUnit\Framework\TestCase;
use SqlMigrator\Statement\Statement;

class StatementTest extends TestCase
{
    public function testShouldCreateStatement(): void
    {
        $command = 'insert into ...';
        $startLine = 1;
        $startPosition = 1;

        $stmt = new Statement(
            $command,
            $command
        );

        $this->assertEquals($command, $stmt->getCommand());
        $this->assertEquals($startLine, $stmt->getStartLine());
        $this->assertEquals($startPosition, $stmt->getStartPosition());
    }
}
