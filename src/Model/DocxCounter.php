<?php

namespace sbolch\WordCounter\Model;

use Exception;
use sbolch\WordCounter\CounterInterface;

class DocxCounter extends DocCounter implements CounterInterface
{
    protected bool $shell;
    protected ?string $tempFile = null;

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
        foreach ($this->getTexts($doc) as $text) {
            $words += str_word_count($text);
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
        foreach ($this->getTexts($doc) as $text) {
            $chars += strlen($text);
        }

        return $chars;
    }

    /**
     * @throws Exception
     */
    protected function init(bool $shell): void
    {
        $this->shell = $shell && `which pandoc` && `which wc`;

        if (!$this->shell && !class_exists(\PhpOffice\PhpWord\IOFactory::class)) {
            throw new Exception('Neither phpoffice/phpword nor pandoc library is available.');
        }
    }

    protected function createTempFile(): void {
        if (!$this->tempFile) {
            $this->tempFile = tempnam(sys_get_temp_dir(), 'sbolch_wordcounter_') . '.txt';
            `pandoc -o $this->tempFile $this->file`;
        }
    }
}
