<?php

namespace sbolch\WordCounter\Model;

use PhpOffice\PhpSpreadsheet\IOFactory;
use sbolch\WordCounter\CounterInterface;

class XlsCounter implements CounterInterface
{
    private int $words;
    private int $chars;

    public function __construct(string $file, bool $shell)
    {
        $spreadsheet = IOFactory::load($file);

        $this->words = 0;
        $this->chars = 0;

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    if ($cell->getValue()) {
                        $content = $cell->getValue();
                        $this->words += str_word_count($content);
                        $this->chars += strlen(trim($content));
                    }
                }
            }
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
