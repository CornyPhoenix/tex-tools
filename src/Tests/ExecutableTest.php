<?php

namespace CornyPhoenix\Tex\Tests;

use CornyPhoenix\Tex\Executables\PdfLuaLatexExecutable;
use CornyPhoenix\Tex\Executables\PdfLatexExecutable;
use CornyPhoenix\Tex\FileFormat;
use CornyPhoenix\Tex\Job;
use CornyPhoenix\Tex\LaTexJob;

/**
 * User: moellers
 * Date: 02.09.14
 * Time: 01:12
 */
class ExecutableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testSupportedInputFileFormats()
    {
        $pdflatex = new PdfLatexExecutable();

        $this->assertTrue($pdflatex->isSupportingInputFormat(FileFormat::TEX));
        $this->assertFalse($pdflatex->isSupportingInputFormat(FileFormat::PDF));
        $this->assertFalse($pdflatex->isSupportingInputFormat(FileFormat::DVI));
        $this->assertFalse($pdflatex->isSupportingInputFormat(FileFormat::POST_SCRIPT));
    }

    /**
     * @test
     */
    public function testSupportedOutputFileFormats()
    {
        $pdflatex = new PdfLatexExecutable();

        $this->assertTrue($pdflatex->isSupportingOutputFormat(FileFormat::PDF));
        $this->assertFalse($pdflatex->isSupportingOutputFormat(FileFormat::DVI));
        $this->assertFalse($pdflatex->isSupportingOutputFormat(FileFormat::TEX));
        $this->assertFalse($pdflatex->isSupportingOutputFormat(FileFormat::POST_SCRIPT));
    }
}
