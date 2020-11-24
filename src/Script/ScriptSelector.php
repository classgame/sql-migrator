<?php

namespace SqlMigrator\Script;

use SqlMigrator\DirectoryMap\MappedFile;

class ScriptSelector
{
    private ScriptPreparer $preparer;

    public function __construct()
    {
        $this->preparer = new ScriptPreparer();
    }

    /**
     * @param MappedFile[] $files
     * @return Script[]
     */
    public function resolveScripts(array $files): array
    {
        return array_map(
            fn (MappedFile $file) => $this->preparer->prepare($file),
            $files
        );
    }
}
