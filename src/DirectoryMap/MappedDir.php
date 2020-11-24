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

    public function getFileList(array $executedList = []): array
    {
        $list = [];

        foreach ($this->files as $file) {
            if ($this->alreadyExecuted($file, $executedList)) {
                continue;
            }

            $list[] = $file;
        }

        foreach ($this->subDirectories as $subDirectory) {
            arr_merge($list, $subDirectory->getFileList($executedList));
        }

        return $list;
    }

    private function alreadyExecuted(
        MappedFile $file,
        array $executedList = []
    ): bool {
        $relativePath = $file->getRelativePath();
        return in_array($relativePath, $executedList, true);
    }

    /**
     * @param array $executedList
     * @return MappedFile[]
     */
    public function getPendingList(array $executedList = []): array
    {
        return $this->getFileList($executedList);
    }
}
