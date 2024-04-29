<?php

namespace sbolch\WordCounter;

interface CounterInterface
{
    public function __construct(string $file, bool $shell);
    public function words(): int;
    public function characters(): int;
}
