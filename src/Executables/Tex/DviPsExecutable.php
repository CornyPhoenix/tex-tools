<?php /** File containing class MakeIndexExecutable */

namespace CornyPhoenix\Tex\Executables\Tex;

use CornyPhoenix\Tex\Executables\AbstractExecutable;
use CornyPhoenix\Tex\FileFormat;
use CornyPhoenix\Tex\Jobs\Job;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Executable class for the MakeIndex command.
 *
 * @package CornyPhoenix\Tex\Executables\Tex
 */
class DviPsExecutable extends AbstractExecutable
{

    /**
     * Returns the file name of the executable.
     *
     * @return string
     */
    public function getName()
    {
        return 'dvips';
    }

    /**
     * Returns the input format that can be processed.
     *
     * @return string
     */
    public function getInputFormat()
    {
        return FileFormat::DVI;
    }

    /**
     * Returns the output formats that can be produced.
     *
     * @return string[]
     */
    public function getOutputFormats()
    {
        return [FileFormat::POST_SCRIPT];
    }

    /**
     * Creates a process for a MakeIndex Job.
     *
     * @param Job $job
     * @return \Symfony\Component\Process\Process
     */
    protected function createProcess(Job $job)
    {
        $processBuilder = new ProcessBuilder();
        $processBuilder->setWorkingDirectory($job->getDirectory());
        $processBuilder->setPrefix($this->getPath());
        $processBuilder->add('-o');
        $processBuilder->add($job->getName() . '.' . $this->getOutputFormats()[0]);
        $processBuilder->add($job->getName() . '.' . $this->getInputFormat());

        return $processBuilder->getProcess();
    }
}
