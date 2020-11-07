<?php

namespace SqlMigrator\Script;

use SqlMigrator\DirectoryMap\DirectoryMap;
use SqlMigrator\DirectoryMap\File;

class ScriptSelector
{
    private ScriptPreparer $preparer;

    public function __construct()
    {
        $this->preparer = new ScriptPreparer();
    }

    /**
     * @param DirectoryMap $dirMap
     * @return Script[]
     */
    public function selectScripts(DirectoryMap $dirMap): array
    {
        $files = $dirMap->getFiles();
        $subDirs = $dirMap->getSubDirectories();
        $scripts = [];

        foreach ($files as $file) {
            $scripts[] = $this->makeScript($file);
        }

        foreach ($subDirs as $subDir) {
            $scriptsDir = $this->selectScripts($subDir);

            foreach ($scriptsDir as $scriptDir) {
                $scripts[] = $scriptDir;
            }
        }

        return $scripts;
    }

    private function makeScript(File $file): Script
    {
        return $this->preparer->prepare($file);
    }
}
