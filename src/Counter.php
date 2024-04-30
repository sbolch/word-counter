<?php

namespace sbolch\WordCounter;

use Exception;
use sbolch\WordCounter\Model\DocCounter;
use sbolch\WordCounter\Model\DocxCounter;
use sbolch\WordCounter\Model\OdtCounter;
use sbolch\WordCounter\Model\PdfCounter;
use sbolch\WordCounter\Model\RtfCounter;
use sbolch\WordCounter\Model\SpreadsheetCounter;
use sbolch\WordCounter\Model\TxtCounter;

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
            'application/rtf', 'text/rtf' => new RtfCounter($filePath, $shellAccess),
            'application/msword', 'application/doc', 'application/ms-doc' => new DocCounter($filePath, $shellAccess),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => new DocxCounter($filePath, $shellAccess),
            'application/vnd.oasis.opendocument.text' => new OdtCounter($filePath, $shellAccess),
            'application/vnd.ms-excel', 'application/excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.oasis.opendocument.spreadsheet', 'text/csv' => new SpreadsheetCounter($filePath, $shellAccess),
            default => throw new Exception('File format not supported ('.mime_content_type($filePath).').'),
        };
    }
}
