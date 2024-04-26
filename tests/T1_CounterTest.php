<?php

namespace Tests;

use sbolch\WordCounter\Counter;
use PHPUnit\Framework\TestCase;
use Exception;

class T1_CounterTest extends TestCase
{
    private string $txtFilePath;
    private string $pdfFilePath;
    private string $docFilePath;
    private string $xlsFilePath;
    private string $unsupportedFilePath;

    public function setUp(): void
    {
        $this->txtFilePath = __DIR__.'/files/test.txt';
        $this->pdfFilePath = __DIR__.'/files/test.pdf';
        $this->docFilePath = __DIR__.'/files/test.docx';
        $this->xlsFilePath = __DIR__.'/files/test.xlsx';
        $this->unsupportedFilePath = __DIR__.'/files/test.png';
    }

    public function testTxt()
    {
        $counter = new Counter($this->txtFilePath);
        $this->assertInstanceOf(Counter::class, $counter);
        $this->assertEquals(91, $counter->words());
    }

    public function testPdf()
    {
        $counter = new Counter($this->pdfFilePath);
        $this->assertInstanceOf(Counter::class, $counter);
        $this->assertEquals(91, $counter->words());
    }

    public function testDoc()
    {
        $counter = new Counter($this->docFilePath);
        $this->assertInstanceOf(Counter::class, $counter);
        $this->assertEquals(91, $counter->words());
    }

    public function testXls()
    {
        $counter = new Counter($this->xlsFilePath);
        $this->assertInstanceOf(Counter::class, $counter);
        $this->assertEquals(91, $counter->words());
    }

    public function testUnsupported()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File format not supported.');
        new Counter($this->unsupportedFilePath);
    }
}