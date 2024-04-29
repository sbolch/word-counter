<?php

namespace sbolch\WordCounter;

use Exception;
use sbolch\WordCounter\Model\DocCounter;
use sbolch\WordCounter\Model\PdfCounter;
use sbolch\WordCounter\Model\TxtCounter;
use sbolch\WordCounter\Model\XlsCounter;

class Counter
{
    /**
     * @throws Exception
     */
    public static function get(string $filePath, bool $shellAccess = true): CounterInterface
    {
        return match (mime_content_type($filePath)) {
            'text/plain' => new TxtCounter($filePath, $shellAccess),
            'application/pdf' => new PdfCounter($filePath, $shellAccess),
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => new DocCounter($filePath, $shellAccess),
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => new XlsCounter($filePath, $shellAccess),
            default => throw new Exception('File format not supported.'),
        };
    }
}
