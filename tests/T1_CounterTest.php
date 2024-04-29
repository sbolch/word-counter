<?php

namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use sbolch\WordCounter\Counter;
use sbolch\WordCounter\Model\DocCounter;
use sbolch\WordCounter\Model\PdfCounter;
use sbolch\WordCounter\Model\TxtCounter;
use sbolch\WordCounter\Model\XlsCounter;

class T1_CounterTest extends TestCase
{
    public function testTxt()
    {
        $counter = Counter::get(__DIR__.'/files/test.txt');
        $this->assertInstanceOf(TxtCounter::class, $counter);
        $this->assertEquals(69, $counter->words());
        $this->assertEquals(445, $counter->characters());
    }

    public function testPdf()
    {
        $counter = Counter::get(__DIR__.'/files/test.pdf');
        $this->assertInstanceOf(PdfCounter::class, $counter);
        $this->assertEquals(928, $counter->words());
        $this->assertEquals(6107, $counter->characters());
    }

    public function testRtf()
    {
        $counter = Counter::get(__DIR__.'/files/test.rtf');
        $this->assertInstanceOf(PdfCounter::class, $counter);
        $this->assertEquals(1084, $counter->words());
        $this->assertEquals(7304, $counter->characters());
    }

    public function testDoc()
    {
        $counter = Counter::get(__DIR__.'/files/test.doc');
        $this->assertInstanceOf(DocCounter::class, $counter);
        $this->assertEquals(913, $counter->words());
        $this->assertEquals(6122, $counter->characters());
    }

    public function testDocx()
    {
        $counter = Counter::get(__DIR__.'/files/test.docx');
        $this->assertInstanceOf(DocCounter::class, $counter);
        $this->assertEquals(913, $counter->words());
        $this->assertEquals(6122, $counter->characters());
    }

    public function testOdt()
    {
        $counter = Counter::get(__DIR__.'/files/test.odt');
        $this->assertInstanceOf(DocCounter::class, $counter);
        $this->assertEquals(913, $counter->words());
        $this->assertEquals(6122, $counter->characters());
    }

    public function testXls()
    {
        $counter = Counter::get(__DIR__.'/files/test.xls');
        $this->assertInstanceOf(XlsCounter::class, $counter);
        $this->assertEquals(90, $counter->words());
        $this->assertEquals(463, $counter->characters());
    }

    public function testXlsx()
    {
        $counter = Counter::get(__DIR__.'/files/test.xlsx');
        $this->assertInstanceOf(XlsCounter::class, $counter);
        $this->assertEquals(90, $counter->words());
        $this->assertEquals(463, $counter->characters());
    }

    public function testOdx()
    {
        $counter = Counter::get(__DIR__.'/files/test.odx');
        $this->assertInstanceOf(XlsCounter::class, $counter);
        $this->assertEquals(90, $counter->words());
        $this->assertEquals(463, $counter->characters());
    }

    public function testUnsupported()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File format not supported.');
        Counter::get(__DIR__.'/files/test.svg');
    }
}