<?php

namespace sbolch\WordCounter\Model;

use Exception;
use sbolch\WordCounter\CounterInterface;

class DocCounter implements CounterInterface
{
    private bool $shell;
    private ?string $tempFile = null;

    /**
     * @throws Exception
     */
    public function __construct(private readonly string $file, bool $shell)
    {
        $this->shell = $shell && `which pandoc` && `which wc`;

        if (!$this->shell && !class_exists(\PhpOffice\PhpWord\IOFactory::class)) {
            throw new Exception('Neither phpoffice/phpword nor pandoc library is available.');
        }
    }

    public function __destruct() {
        if ($this->tempFile) {
            @unlink($this->tempFile);
        }
    }

    public function words(): int
    {
        if ($this->shell) {
            $this->createTempFile();
            return intval(`wc -w $this->tempFile`);
        }

        $doc = \PhpOffice\PhpWord\IOFactory::load($this->file);

        $words = 0;
        foreach ($doc->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $words += str_word_count($element->getText());
                }
            }
        }

        return $words;
    }

    public function characters(): int
    {
        if ($this->shell) {
            $this->createTempFile();
            return intval(`wc -m $this->tempFile`);
        }

        $doc = \PhpOffice\PhpWord\IOFactory::load($this->file);

        $chars = 0;
        foreach ($doc->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $chars += strlen(trim($element->getText()));
                }
            }
        }

        return $chars;
    }

    private function createTempFile(): void {
        if (!$this->tempFile) {
            $this->tempFile = tempnam(sys_get_temp_dir(), 'sbolch_wordcounter_') . '.txt';
            `pandoc -o $this->tempFile $this->file`;
        }
    }
}
