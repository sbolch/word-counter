<?php

namespace sbolch\WordCounter;

interface CounterInterface
{
    public function __construct(string $file);
    public function words(): int;
    public function characters(): int;
}
