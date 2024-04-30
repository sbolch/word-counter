<?php

namespace sbolch\WordCounter\Model;

use Exception;
use sbolch\WordCounter\CounterInterface;

class RtfCounter extends DocxCounter implements CounterInterface
{
    /**
     * @throws Exception
     */
    public function words(): int
    {
        if ($this->shell) {
            $this->createTempFile();
            return intval(`wc -w $this->tempFile`);
        }

        $doc = (new \PhpOffice\PhpWord\Reader\RTF())->load($this->file);

        $words = 0;
        foreach ($this->getTexts($doc) as $text) {
            $words += str_word_count($text);
        }

        return $words;
    }

    /**
     * @throws Exception
     */
    public function characters(): int
    {
        if ($this->shell) {
            $this->createTempFile();
            return intval(`wc -m $this->tempFile`);
        }

        $doc = (new \PhpOffice\PhpWord\Reader\RTF())->load($this->file);

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

        if (!$this->shell && !class_exists(\PhpOffice\PhpWord\Reader\RTF::class)) {
            throw new Exception('Neither phpoffice/phpword nor pandoc library is available.');
        }
    }
}
