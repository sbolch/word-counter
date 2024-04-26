<?php

namespace sbolch\WordCounter\Model;

use Exception;
use sbolch\WordCounter\CounterInterface;

class DocCounter implements CounterInterface
{
    private int $words;
    private int $chars;

    /**
     * @throws Exception
     */
    public function __construct(string $file)
    {
        if (`which pandoc` && `which wc`) {
            $tempfile = tempnam(sys_get_temp_dir(), 'sbolch_wordcounter_').'.txt';
            `pandoc -o $tempfile $file`;

            $this->words = intval(`wc -w $tempfile`);
            $this->chars = intval(`wc -m $tempfile`);

            @unlink($tempfile);
        } else {
            if (!class_exists(\PhpOffice\PhpWord\IOFactory::class)) {
                throw new Exception('Neither phpoffice/phpword nor pandoc library is available.');
            }

            $doc = \PhpOffice\PhpWord\IOFactory::load($file);

            $this->words = 0;
            $this->chars = 0;

            foreach ($doc->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        $content = $element->getText();
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
