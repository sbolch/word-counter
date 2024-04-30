<?php

namespace sbolch\WordCounter\Model;

use Exception;
use sbolch\WordCounter\CounterInterface;

class DocCounter implements CounterInterface
{
    /**
     * @throws Exception
     */
    public function __construct(private readonly string $file, bool $shell)
    {
        if (!class_exists(\PhpOffice\PhpWord\Reader\MsDoc::class)) {
            throw new Exception('phpoffice/phpword library is not available.');
        }
    }

    public function words(): int
    {
        $doc = (new \PhpOffice\PhpWord\Reader\MsDoc())->load($this->file);

        $words = 0;
        foreach ($this->getTexts($doc) as $text) {
            $words += str_word_count($text);
        }

        return $words;
    }

    public function characters(): int
    {
        $doc = (new \PhpOffice\PhpWord\Reader\MsDoc())->load($this->file);

        $chars = 0;
        foreach ($this->getTexts($doc) as $text) {
            $chars += strlen($text);
        }

        return $chars;
    }

    protected function getTexts(object $doc): iterable
    {
        foreach ($doc->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    yield trim($element->getText());
                }
            }
        }
    }
}
