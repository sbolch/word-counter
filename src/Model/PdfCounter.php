<?php

namespace sbolch\WordCounter\Model;

use Exception;
use sbolch\WordCounter\CounterInterface;
use Smalot\PdfParser\Parser;

class PdfCounter implements CounterInterface
{
    private int $words;
    private int $chars;

    /**
     * @throws Exception
     */
    public function __construct(string $file)
    {
        $pdf = (new Parser())->parseFile($file);
        $pages = $pdf->getPages();

        $this->words = 0;
        $this->chars = 0;

        foreach ($pages as $page) {
            $content = $page->getText();
            $this->words += str_word_count($content);
            $this->chars += strlen(trim($content));
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
