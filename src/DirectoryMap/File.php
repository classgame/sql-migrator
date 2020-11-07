<?php

namespace SqlMigrator\DirectoryMap;

class File
{
    private string $path;
    private string $fileName;

    public function __construct(string $path, string $fileName)
    {
        $this->path = $path;
        $this->fileName = $fileName;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
}
