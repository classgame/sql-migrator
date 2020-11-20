<?php

namespace SqlMigrator\DirectoryMap;

class MappedDir
{
    private string $path;
    private string $migrationPath;
    private string $relativePath;
    private string $dirName;
    /** @var MappedDir[] */
    private array $subDirectories;
    /** @var MappedFile[] */
    private array $files;

    public function __construct(string $path, string $migrationPath)
    {
        $this->path = $path;
        $this->migrationPath = $migrationPath;
        $this->files = [];
        $this->subDirectories = [];

        $this->setDirName($path);
        $this->setRelativePath($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function addFiles(MappedFile $file): self
    {
        $this->files[] = $file;
        return $this;
    }

    /**
     * @return MappedFile[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    public function addSubDir(MappedDir $dir): self
    {
        $this->subDirectories[] = $dir;
        return $this;
    }

    /**
     * @return MappedDir[]
     */
    public function getSubDirectories(): array
    {
        return $this->subDirectories;
    }

    private function setDirName(string $path): void
    {
        $parts = explode('/', $path);
        $lastKey = array_key_last($parts);
        $this->dirName = $parts[$lastKey];
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function setRelativePath(string $path): self
    {
        $relativePath = substr($path, strlen($this->migrationPath));
        $this->relativePath = $relativePath === '' ? '/' : $relativePath;
        return $this;
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

    public function getMigrationPath(): string
    {
        return $this->migrationPath;
    }

    public function __toString(): string
    {
        return $this->migrationPath;
    }

    public function getFileList(): array
    {
        $list = [];

        foreach ($this->files as $file) {
            $list[] = $file->getRelativePath();
        }

        foreach ($this->subDirectories as $subDirectory) {
            $this->merge($list, $subDirectory->getFileList());
        }

        return $list;
    }

    private function merge(array &$source, array $add): void
    {
        foreach ($add as $item) {
            $source[] = $item;
        }
    }

    public function getPendingScripts(array $executedList = []): array
    {
        return array_diff($this->getFileList(), $executedList);
    }
}
