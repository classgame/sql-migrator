<?php

namespace SqlMigrator\Finder;

class Finder
{
    public function find(string $dirPath): Directory
    {
        $this->validate($dirPath);

        $dir = new Directory($dirPath);
        $items = scandir($dirPath);

        foreach ($items as $fileName) {
            $path = realpath($dirPath . DIRECTORY_SEPARATOR . $fileName);

            if ($this->isToIgnore($fileName)) {
                continue;
            }

            if (is_file($path) && !$this->isSqlFile($fileName)) {
                continue;
            }

            if (is_dir($path)) {
                $subDir = $this->find($path);
                $dir->addSubDir($subDir);
                continue;
            }

            $file = new File($path, $fileName);
            $dir->addFiles($file);
        }

        return $dir;
    }

    private function validate(string $dir): void
    {
        if (!is_dir($dir)) {
            $msg = "$dir is not a directory";
            throw new \InvalidArgumentException($msg);
        }
    }

    private function isSqlFile($file): bool
    {
        $parts = explode('.', $file);

        if (count($parts) <= 1) {
            return false;
        }

        $lastKey = array_key_last($parts);

        if (is_null($lastKey)) {
            return false;
        }

        $ext = $parts[$lastKey];

        return $ext === 'sql';
    }

    private function isToIgnore(string $file): bool
    {
        return $file === '.' || $file === '..';
    }
}
