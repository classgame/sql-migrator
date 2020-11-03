<?php

namespace SqlMigrator\Statement;

class Statement
{
    private string $command;
    private int $startLine;
    private int $startPosition;

    public function __construct(
        string $command,
        int $startLine,
        int $startPosition
    ) {
        $this->command = $command;
        $this->startLine = $startLine;
        $this->startPosition = $startPosition;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getStartLine(): int
    {
        return $this->startLine;
    }

    public function getStartPosition(): int
    {
        return $this->startPosition;
    }
}
