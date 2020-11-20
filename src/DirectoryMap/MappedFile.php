<?php

namespace SqlMigrator\DirectoryMap;

class MappedFile
{
    private string $path;
    private string $relativePath;
    private string $fileName;

    public function __construct(string $path, string $relativePathDir, string $fileName)
    {
        $this->path = $path;
        $this->relativePath = $relativePathDir . '/' . $fileName;
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

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function __toString(): string
    {
        return $this->relativePath;
    }
}
