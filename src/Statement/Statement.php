<?php

namespace SqlMigrator\Statement;

use Core\Util\StringUtil;
use SqlMigrator\Exception\MatchCommandFailException;

class Statement
{
    private string $command;
    private int $startLine;
    private int $startPosition;

    public function __construct(string $content, string $command)
    {
        $this->command = $command;
        $this->setStartLine($content);
        $this->setStartPosition($content);
    }

    private function setStartPosition(string $content): void
    {
        $command = $this->command;

        $linePosition = StringUtil::getPositionFirstLineWithContent($command);
        $commandLines = StringUtil::filterNoBlankLines($command);
        $firstLineCommand = $commandLines[$linePosition];

        $linePosition = StringUtil::getPositionFirstLineWithContent($content);
        $contentLines = StringUtil::filterNoBlankLines($content);
        $firstLineContent = $contentLines[$linePosition];

        $startPosition = strpos($firstLineContent, $firstLineCommand);

        if ($startPosition === false) {
            throw new MatchCommandFailException($command);
        }

        $this->startPosition = $startPosition + 1;
    }

    private function setStartLine(string $content): void
    {
        $this->startLine = StringUtil
                ::getPositionFirstLineWithContent($content) + 1;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getStartLine(): int
    {
        return $this->startLine;
    }

    public function getStartPosition(): int
    {
        return $this->startPosition;
    }
}
