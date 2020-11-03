<?php

namespace SqlMigrator\Statement;

use SqlMigrator\Exception\MatchCommandFailException;

class ScriptPreparer
{
    public function prepare(string $path): Script
    {
        $script = new Script($path);
        $this->breakIntoStatements($script);

        return $script;
    }

    private function breakIntoStatements(Script $script): void
    {
        $path = $script->getPath();
        $content = file_get_contents($path);
        $commands = $this->getCommands($content);

        foreach ($commands as $command) {
            // TODO fix start line
            $startPosition = $this->getStartPosition($content, $command);
            $startLine = $this->getStartLine($content);
            $content = $this->removeCommandFromContent($command, $content);

            $statement = new Statement(
                $command,
                $startLine,
                $startPosition
            );

            $script->addStatement($statement);
        }

        dd($script->getStatements());
    }

    private function getStartPosition(string $content, string $command): int
    {
        $linePosition = $this->getNumberFirstLineWithContent($command);
        $commandLines = $this->filterNoBlankLines($command);
        $firstLineCommand = $commandLines[$linePosition];

        $linePosition = $this->getNumberFirstLineWithContent($content);
        $contentLines = $this->filterNoBlankLines($content);
        $firstLineContent = $contentLines[$linePosition];

        $startPosition = strpos($firstLineContent, $firstLineCommand);

        if ($startPosition === false) {
            throw new MatchCommandFailException($command);
        }

        return $startPosition + 1;
    }

    private function replaceIntoQuotes(
        string $search,
        string $to,
        string $content
    ): string {
        $pattern = "/\"[^\"]*\"|'[^']*'/";

        preg_match_all($pattern, $content, $matches);
        $replaced = $content;

        foreach ($matches[0] as $match) {
            $matchReplaced = str_replace($search, $to, $match);
            $replaced = $this->replaceFirst($match, $matchReplaced, $replaced);
        }

        return $replaced;
    }

    private function removeCommandFromContent(
        string $command,
        string $content
    ): string {
        $replace = $this->convertInBlankSpaces($command);
        return $this->replaceFirst($command, $replace, $content);
    }

    private function convertInBlankSpaces(string $str): string
    {
        $lines = explode("\n", $str);
        $countLines = count($lines);
        $blank = '';

        foreach ($lines as $i => $line) {
            $isLastLoop = $i + 1 === $countLines;
            $blank .= str_repeat(' ', strlen($line));

            if (!$isLastLoop) {
                $blank .= "\n";
            }
        }

        return $blank;
    }

    private function getStartLine(string $content): int
    {
        return $this->getNumberFirstLineWithContent($content) + 1;
    }

    private function getNumberFirstLineWithContent(string $content): int
    {
        $noBlankLines = $this->filterNoBlankLines($content);
        return array_key_first($noBlankLines);
    }

    private function filterNoBlankLines(string $content): array
    {
        $lines = explode("\n", $content);

        return array_filter($lines, static function ($line) {
            return trim($line) !== '' && trim($line) !== "\n";
        });
    }

    private function replaceFirst($from, $to, $content): string
    {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $content, 1);
    }

    private function getCommands(string $content): array
    {
        $pattern = '/[a-z][\S\s]*?(?=;)./';
        $content = $this->occultSemiColon($content);
        preg_match_all($pattern, $content, $matches);
        return $this->recoverySemiColon($matches[0]);
    }

    private function recoverySemiColon(array $commands): array
    {
        $search = '{{ semi_colon }}';
        $to = ';';
        $recovered = [];

        foreach ($commands as $command) {
            $recovered[] = $this->replaceIntoQuotes($search, $to, $command);
        }

        return $recovered;
    }

    private function occultSemiColon(string $content): string
    {
        $search = ';';
        $to = '{{ semi_colon }}';
        return $this->replaceIntoQuotes($search, $to, $content);
    }
}
