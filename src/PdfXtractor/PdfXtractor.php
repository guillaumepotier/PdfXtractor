<?php

namespace PdfXtractor;

class PdfXtractor implements PdfXtractorInterface
{
    private $gsBin;
    private $gsPath = false;

    private $pdfFile = false;
    private $outputDir = false;
    private $outputName = false;

    private $extractMsg = null;

    public function __construct($gsBin = 'gs', $gsPath = false)
    {
        $this->gsBin = $gsBin;
        $this->gsPath = substr($gsPath, -1) == '/' ? $gsPath : $gsPath.'/';;

        if (false === $gsPath) {
            $this->gsPath = __DIR__.'/../../bin/';
        }

        if (!file_exists($this->gsPath.$gsBin)) {
            throw new \Exception("GS bin not found");
        }
    }

    public function load($pdfFile) {
        if (!file_exists($pdfFile)) {
            throw new \Exception("Input file not found");
        }

        $this->pdfFile = $pdfFile;
        return $this;
    }

    public function set($outputDir, $outputName)
    {
        clearstatcache();
        if (!is_dir($outputDir)) {
            if (!@mkdir($outputDir, 0777, true)) {
                throw new \Exception("Unable to create the output directory");
            }
        }

        $this->outputDir = substr($outputDir, -1) == '/' ? $outputDir : $outputDir.'/';
        $this->outputName = $outputName;

        return $this;
    }

    public function extract($async = false)
    {
        if (false === $this->pdfFile || false === $this->outputDir || false === $this->outputName) {
            throw new \Exception("You must give a pdf to convert, an output dir and an output name");
        }

        $cmd = "{$this->gsBin} -dNOPAUSE -dSAFER -dBATCH -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4";
        $cmd .= " -r72";
        $cmd .= " -sOutputFile={$this->outputDir}{$this->outputName}-%d.jpg {$this->pdfFile}";

        if (true === $async) {
            $cmd .= " > /dev/null 2>/dev/null &";
        }

        return $this->gs($cmd, $async);
    }

    private function gs($cmd, $async)
    {
        $lastLine = exec($this->gsPath.$cmd, $output);

        if (true === $async) {
            return true;
        }

        $this->extractMsg = array_slice($output, 3);
        preg_match_all("/([0-9]+)/", $this->extractMsg[0], $matches);

        return $lastLine == "Page ".$matches[0][1];
    }

    public function getExtractMsg()
    {
        return $this->extractMsg;
    }
}