<?php

namespace SqlMigrator\Statement;

use Core\Util\StringUtil;

class Content
{
    private string $content;
    private array $commands;

    public function __construct(string $content)
    {
        $this->content = $content;
        $this->commands = $this->identifyCommands();
    }

    private function identifyCommands(): array
    {
        $pattern = '/[a-z][\S\s]*?(?=;)./';
        $content = $this->occultSemiColon();
        preg_match_all($pattern, $content, $matches);
        return $this->recoverySemiColon($matches[0]);
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    private function occultSemiColon(): string
    {
        $search = ';';
        $to = '{{ semi_colon }}';
        return StringUtil::replaceIntoQuotes($search, $to, $this->content);
    }

    public function recoverySemiColon(array $commands): array
    {
        $search = '{{ semi_colon }}';
        $to = ';';
        $recovered = [];

        foreach ($commands as $command) {
            $recovered[] = StringUtil::replaceIntoQuotes(
                $search,
                $to,
                $command
            );
        }

        return $recovered;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
