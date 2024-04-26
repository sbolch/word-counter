<?php

namespace sbolch\WordCounter;

use Exception;
use sbolch\WordCounter\Model\DocCounter;
use sbolch\WordCounter\Model\PdfCounter;
use sbolch\WordCounter\Model\TxtCounter;
use sbolch\WordCounter\Model\XlsCounter;

class Counter
{
    private CounterInterface $counter;

    /**
     * @throws Exception
     */
    public function __construct(string $filePath)
    {
        $this->counter = match (mime_content_type($filePath)) {
            'text/plain' => new TxtCounter($filePath),
            'application/pdf' => new PdfCounter($filePath),
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => new DocCounter($filePath),
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => new XlsCounter($filePath),
            default => throw new Exception('File format not supported.'),
        };
    }

    public function words(): int
    {
        return $this->counter->words();
    }

    public function characters(): int
    {
        return $this->counter->characters();
    }
}
