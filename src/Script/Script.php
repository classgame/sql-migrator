<?php

namespace SqlMigrator\Script;

use SqlMigrator\Exception\InvalidFileException;

class Script
{
    private ?string $version;
    private string $path;
    private string $relativePath;
    private string $name;
    private int $numberOfLines;
    private array $statements;
    private Content $content;

    public function __construct(
        string $path,
        string $relativePath,
        string $name
    ) {
        if (!is_file($path)) {
            throw new InvalidFileException($path);
        }

        $this->version = $this->extractVersion($relativePath);
        $this->path = $path;
        $this->relativePath = $relativePath;
        $this->name = $name;
        $this->numberOfLines = (int)count(file($path));
        $this->content = new Content(file_get_contents($path));
    }

    private function extractVersion(string $relativePath): ?string
    {
        $arr = explode(DIRECTORY_SEPARATOR, $relativePath);
        $size = count($arr);
        $penultimateIndex = $size - 2;

        if ($size < 2) {
            $msg = "Version not found in file path '$relativePath'";
            throw new \RuntimeException($msg);
        }

        return array_get($arr, $penultimateIndex);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Statement[]
     */
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }
}
