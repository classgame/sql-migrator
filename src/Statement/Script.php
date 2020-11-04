<?php

namespace SqlMigrator\Statement;

use SqlMigrator\Exception\InvalidFileException;

class Script
{
    private string $path;
    private int $numberOfLines;
    private array $statements;
    private Content $content;

    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new InvalidFileException($path);
        }

        $this->path = $path;
        $this->numberOfLines = (int)count(file($path));
        $this->content = new Content(file_get_contents($path));
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getLineQuantity(): int
    {
        return $this->numberOfLines;
    }

    public function getStatements(): array
    {
        return $this->statements;
    }

    public function addStatement(Statement $statement): self
    {
        $this->statements[] = $statement;
        return $this;
    }

    public function getNumberOfLines(): int
    {
        return $this->numberOfLines;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCommands(): array
    {
        return $this->content->getCommands();
    }
}
