<?php

namespace SqlMigrator\Script;

use Core\Util\StringUtil;
use SqlMigrator\DirectoryMap\File;

class ScriptPreparer
{
    public function prepare(File $file): Script
    {
        $path = $file->getPath();
        $fileName = $file->getFileName();

        $script = new Script($path, $fileName);
        $this->breakIntoStatements($script);

        return $script;
    }

    private function breakIntoStatements(Script $script): void
    {
        $content = $script->getContent();
        $commands = $script->getCommands();

        foreach ($commands as $command) {
            $statement = new Statement($content, $command);
            $content = $this->removeCommandFromContent($command, $content);
            $script->addStatement($statement);
        }
    }

    private function removeCommandFromContent(
        string $command,
        string $content
    ): string {
        $replace = $this->convertInBlankSpaces($command);
        return StringUtil::replaceFirst($command, $replace, $content);
    }

    private function convertInBlankSpaces(string $str): string
    {
        $lines = explode("\n", $str);
        $countLines = count($lines);
        $blank = '';

        foreach ($lines as $i => $line) {
            $isLastLoop = $i + 1 === $countLines;
            $blank .= str_repeat(' ', strlen($line) - 1);

            if (!$isLastLoop) {
                $blank .= "\n";
            }
        }

        return $blank;
    }
}
