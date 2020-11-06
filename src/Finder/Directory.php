<?php

namespace SqlMigrator\Finder;

class Directory
{
    private string $path;
    private string $dirName;
    private array $files;
    private array $subDirectories;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->files = [];
        $this->subDirectories = [];

        $this->setDirName($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function addFiles(File $file): self
    {
        $this->files[] = $file;
        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function addSubDir(Directory $dir): self
    {
        $this->subDirectories[] = $dir;
        return $this;
    }

    /**
     * @return Directory[]
     */
    public function getSubDirectories(): array
    {
        return $this->subDirectories;
    }

    private function setDirName(string $dir): void
    {
        $parts = explode('/', $dir);
        $lastKey = array_key_last($parts);
        $this->dirName = $parts[$lastKey];
    }

    public function getDirName(): string
    {
        return $this->dirName;
    }

    public function getSubDirCount(): int
    {
        return count($this->subDirectories);
    }

    public function getFilesCount(): int
    {
        return count($this->files);
    }
}
