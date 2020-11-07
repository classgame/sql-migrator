<?php

namespace SqlMigrator\Script;

use SqlMigrator\Finder\Directory;
use SqlMigrator\Finder\File;

class ScriptSelector
{
    private ScriptPreparer $preparer;

    public function __construct()
    {
        $this->preparer = new ScriptPreparer();
    }

    /**
     * @param Directory $dir
     * @return Script[]
     */
    public function selectScripts(Directory $dir): array
    {
        $files = $dir->getFiles();
        $subDirs = $dir->getSubDirectories();
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
