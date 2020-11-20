<?php

namespace SqlMigrator\Script;

use SqlMigrator\DirectoryMap\MappedDir;
use SqlMigrator\DirectoryMap\MappedFile;

class ScriptSelector
{
    private ScriptPreparer $preparer;

    public function __construct()
    {
        $this->preparer = new ScriptPreparer();
    }

    /**
     * @param MappedDir $dirMap
     * @return Script[]
     */
    public function selectScripts(MappedDir $dirMap): array
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

    private function makeScript(MappedFile $file): Script
    {
        return $this->preparer->prepare($file);
    }
}
