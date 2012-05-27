<?php

namespace PdfXtractor\Tests;

use PdfXtractor\PdfXtractor as PdfXtractor;

class PdfXtractorTest extends \PHPUnit_Framework_TestCase
{
    protected $pdfXtractor;
    protected $input;
    protected $output;

    protected function setUp()
    {
        $this->pdfXtractor = new PdfXtractor(isset($_SERVER['GS_BIN']) ? $_SERVER['GS_BIN'] : false);
        $this->input = __DIR__.'/../input';
        $this->output = __DIR__.'/../output';
        chmod($this->output, 0777);
    }

    public function testSetUp()
    {
        try {
            $pdfXtractor = new PdfXtractor('wrong_bin');
            $this->fail();
        } catch (\Exception $e) {
            $this->isTrue();
        }
    }

    public function testLoad()
    {
        try {
            $this->pdfXtractor->load($this->input.'/test_fail.pdf');
            $this->fail();
        } catch (\Exception $e) {
            $this->isTrue();
        }

        $load = $this->pdfXtractor->load($this->input.'/test.pdf');
        $this->assertSame($load, $this->pdfXtractor);
    }

    public function testSet()
    {
        $set = $this->pdfXtractor->set($this->output.'/newdir', 'test');
        $this->assertSame($set, $this->pdfXtractor);
        rmdir($this->output.'/newdir');

        chmod($this->output, 0444);
        try {
            $set = $this->pdfXtractor->set($this->output.'/newdir', 'test');
            $this->fail();
        } catch (\Exception $e) {
            $this->isTrue();
        }
        chmod($this->output, 0777);

        $set = $this->pdfXtractor->set($this->output, 'test');
        $this->assertSame($set, $this->pdfXtractor);
    }

    public function testExtract()
    {
        try {
            $this->pdfXtractor->set($this->output, false)->extract();
            $this->fail();
        } catch (\Exception $e) {
            $this->isTrue();
        }

        $extract = $this->pdfXtractor->load($this->input.'/test1.pdf')->set($this->output, 'test1')->extract(true);
        $this->assertEquals($extract, true);

        $extract = $this->pdfXtractor->extract();
        $this->assertEquals(4, count($extract));

        $extract = $this->pdfXtractor->set($this->output, 'test');
        $this->assertEquals(1, count($extract));
    }

    protected function tearDown()
    {
        passthru("rm -rf {$this->output}/*");
    }
}