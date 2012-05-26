<?php

namespace PdfXtractor;

interface PdfXtractorInterface
{
    function load($pdfFile);

    function set($outputDir, $outputName);

    function extract($async);
}