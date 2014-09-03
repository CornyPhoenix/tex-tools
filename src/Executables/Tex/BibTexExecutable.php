<?php /** File containing class BibTexExecutable */

namespace CornyPhoenix\Tex\Executables\Tex;

use CornyPhoenix\Tex\Executables\AbstractExecutable;
use CornyPhoenix\Tex\FileFormat;
use CornyPhoenix\Tex;
use CornyPhoenix\Tex\Jobs\Job;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Executable class for the BibTeX command.
 *
 * @package CornyPhoenix\Tex\Executables\Tex
 */
class BibTexExecutable extends AbstractExecutable
{

    /**
     * Returns the file name of the executable.
     *
     * @return string
     */
    public function getName()
    {
        return 'bibtex';
    }

    /**
     * Returns the input format that can be processed.
     *
     * @return string
     */
    public function getInputFormat()
    {
        return FileFormat::AUXILIARY;
    }

    /**
     * Returns the output formats that can be produced.
     *
     * @return string[]
     */
    public function getOutputFormats()
    {
        return [FileFormat::BIBLIOGRAPHY_ENTRIES, FileFormat::BIBLIOGRAPHY_LOG];
    }

    /**
     * Creates a process for a BibTeX Job.
     *
     * @param Job $job
     * @return \Symfony\Component\Process\Process
     */
    protected function createProcess(Job $job)
    {
        $processBuilder = new ProcessBuilder();
        $processBuilder->setWorkingDirectory($job->getDirectory());
        $processBuilder->setPrefix($this->getPath());
        $processBuilder->add($job->getName() . '.' . $this->getInputFormat());

        return $processBuilder->getProcess();
    }
}
