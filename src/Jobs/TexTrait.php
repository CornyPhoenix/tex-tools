<?php /** File containing trait TexJob */

namespace CornyPhoenix\Tex\Jobs;

use CornyPhoenix\Tex\Executables\Tex\BibTex8Executable;
use CornyPhoenix\Tex\Executables\Tex\BibTexExecutable;
use CornyPhoenix\Tex\Executables\Tex\MakeIndexExecutable;
use CornyPhoenix\Tex\Executables\Tex\PdfTexExecutable;
use CornyPhoenix\Tex\Executables\Tex\TexExecutable;
use CornyPhoenix\Tex\FileFormat;

/**
 * This trait provides TeX functionality for the Job class.
 *
 * @package CornyPhoenix\Tex\Jobs
 * @date 03.09.2014
 * @author moellers
 */
trait TexTrait
{

    /**
     * Runs the TeX command.
     *
     * @param callable $callback
     * @return $this
     */
    public function runTex(callable $callback = null)
    {
        return $this->run(TexExecutable::class, $callback);
    }

    /**
     * Runs the PdfTeX command.
     *
     * @param callable $callback
     * @return $this
     */
    public function runPdfTex(callable $callback = null)
    {
        return $this->run(PdfTexExecutable::class, $callback);
    }

    /**
     * Runs the MakeIndex command.
     *
     * @param callable $callback
     * @return $this
     */
    public function runMakeIndex(callable $callback = null)
    {
        $this->addProvidedFormats([FileFormat::INDEX]);

        return $this->run(MakeIndexExecutable::class, $callback);
    }
}