<?php

namespace SqlMigrator\DirectoryMap;

class Mapper
{
    public function mapper(string $dirPath): DirectoryMap
    {
        $this->validate($dirPath);

        $dirMap = new DirectoryMap($dirPath);
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
                $subDir = $this->mapper($path);
                $dirMap->addSubDir($subDir);
                continue;
            }

            $file = new File($path, $fileName);
            $dirMap->addFiles($file);
        }

        return $dirMap;
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
