<?php

namespace sbolch\WordCounter\Model;

use sbolch\WordCounter\CounterInterface;

class TxtCounter implements CounterInterface
{
    private int $words;
    private int $chars;

    public function __construct(string $file)
    {
        if (`which wc`) {
            $this->words = intval(`wc -w $file`);
            $this->chars = intval(`wc -m $file`);
        } else {
            $content = file_get_contents($file);
            $this->words = str_word_count($content);
            $this->chars = strlen($content);
        }
    }

    public function words(): int
    {
        return $this->words;
    }

    public function characters(): int
    {
        return $this->chars;
    }
}
