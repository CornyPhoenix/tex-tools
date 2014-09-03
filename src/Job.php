<?php

namespace CornyPhoenix\Tex;

use CornyPhoenix\Tex\Exceptions\LogicException;
use CornyPhoenix\Tex\Executables\ExecutableInterface;
use CornyPhoenix\Tex\Results\ErrorResult;
use CornyPhoenix\Tex\Results\ResultInterface;
use InvalidArgumentException;

class Job
{

    /**
     * @var ExecutableInterface[]
     */
    private static $executables = array();

    /**
     * @var bool
     */
    private $hasErrors;

    /**
     * @var ResultInterface
     */
    private $result;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $directory;

    /**
     * An alternative jobname which will be passed to TeX.
     *
     * @var string|null
     */
    private $jobname;

    /**
     * The Mode in which the job will be executed.
     *
     * @var string
     */
    private $interactionMode;

    /**
     * @var string|null
     */
    private $outputDirectory;

    /**
     * @var bool
     */
    private $draftMode;

    /**
     * @var bool
     */
    private $shellEscape;

    /**
     * @var int|null
     */
    private $syncTex;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->setPath($path);
        $this->setDefaults();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->hasErrors;
    }

    /**
     * @throws LogicException
     * @return ResultInterface
     */
    public function getResult()
    {
        if (null === $this->result) {
            throw new LogicException('You have to run this job first.');
        }

        return $this->result;
    }

    /**
     * @return null|string
     */
    public function getJobname()
    {
        return $this->jobname;
    }

    /**
     * @param null|string $jobname
     * @return $this
     */
    public function setJobname($jobname)
    {
        $this->jobname = $jobname;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDraftMode()
    {
        return $this->draftMode;
    }

    /**
     * @param bool $draftMode
     * @return $this
     */
    public function setDraftMode($draftMode)
    {
        $this->draftMode = boolval($draftMode);
        return $this;
    }

    /**
     * @return string
     */
    public function getInteractionMode()
    {
        return $this->interactionMode;
    }

    /**
     * @param string $interactionMode
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setInteractionMode($interactionMode)
    {
        if (!InteractionMode::modeExists($interactionMode)) {
            throw new InvalidArgumentException('You specified a wring interaction mode "' . $interactionMode . '"');
        }

        $this->interactionMode = $interactionMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputDirectory()
    {
        return $this->outputDirectory;
    }

    /**
     * @param string $outputDirectory
     * @return $this
     */
    public function setOutputDirectory($outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getShellEscape()
    {
        return $this->shellEscape;
    }

    /**
     * @param boolean $shellEscape
     * @return $this
     */
    public function setShellEscape($shellEscape)
    {
        $this->shellEscape = boolval($shellEscape);
        return $this;
    }

    /**
     * @return int
     */
    public function getSyncTex()
    {
        return $this->syncTex;
    }

    /**
     * @param int|null $syncTex
     * @return $this
     */
    public function setSyncTex($syncTex)
    {
        if (null === $syncTex) {
            $this->syncTex = null;
        } else {
            $this->syncTex = intval($syncTex);
        }

        return $this;
    }

    /**
     * @param string $executableClass
     * @param callable $callback
     * @return $this
     */
    public function run($executableClass, callable $callback = null)
    {
        if (!$this->hasErrors) {
            $executable = $this->findExecutableByClass($executableClass);

            $process = $executable->runJob($this, $callback);
            if (1 === $process->getExitCode()) {
                $this->hasErrors = true;
                $this->result = $this->createErrorResult($process->getOutput());
            }
        }

        return $this;
    }

    /**
     * Sets default values on the job.
     */
    private function setDefaults()
    {
        $this->interactionMode = InteractionMode::NONSTOP_MODE;
        $this->outputDirectory = null;
        $this->jobname = null;
        $this->syncTex = null;
        $this->shellEscape = false;
        $this->draftMode = false;
        $this->hasErrors = false;
        $this->result = null;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    private function setPath($path)
    {
        $this->path = $path;
        $this->directory = dirname($path);
        $this->name = basename($path);

        return $this;
    }

    /**
     * @param string $executableClass
     * @return \CornyPhoenix\Tex\Executables\ExecutableInterface
     */
    private function findExecutableByClass($executableClass)
    {
        if (!isset(self::$executables[$executableClass])) {
            self::$executables[$executableClass] = new $executableClass();
        }

        return self::$executables[$executableClass];
    }

    /**
     * @param string $output
     * @return ErrorResult
     */
    private function createErrorResult($output)
    {
        return new ErrorResult($output);
    }
}
