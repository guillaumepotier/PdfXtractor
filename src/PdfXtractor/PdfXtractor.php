<?php

namespace PdfXtractor;

class PdfXtractor implements PdfXtractorInterface
{
    private $pdfFile = false;
    private $gsPath = false;
    private $outputDir = false;
    private $outputName = false;

    public function __construct($gsPath = false)
    {
        $this->gsPath = $gsPath;

        if (false === $gsPath) {
            $this->gsPath = __DIR__.'/../../bin/';
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

        $cmd = "gs -dNOPAUSE -dSAFER -dBATCH -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4";
        $cmd .= " -r72";
        $cmd .= " -sOutputFile={$this->outputDir}{$this->outputName}-%d.jpg {$this->pdfFile}";

        if (true === $async) {
            $cmd .= " > /dev/null 2>/dev/null &";
        }

        exec($this->gsPath.$cmd, $output);

        return false === $async ? array_slice($output, 3) : true;
    }
}