<?php

namespace sbolch\WordCounter\Model;

use sbolch\WordCounter\CounterInterface;

class TxtCounter implements CounterInterface
{
    private bool $shell;

    public function __construct(private readonly string $file, bool $shell)
    {
        $this->shell = $shell && `which wc`;
    }

    public function words(): int
    {
        return $this->shell
            ? intval(`wc -w $this->file`)
            : str_word_count(file_get_contents($this->file));
    }

    public function characters(): int
    {
        return $this->shell
            ? intval(`wc -m $this->file`)
            : strlen(file_get_contents($this->file));
    }
}
