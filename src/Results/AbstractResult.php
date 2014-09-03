<?php

namespace CornyPhoenix\Tex\Results;

use CornyPhoenix\Tex\Message;

/**
 * User: moellers
 * Date: 02.09.14
 * Time: 19:30
 */
abstract class AbstractResult implements ResultInterface
{

    /**
     * @var string
     */
    private $version;

    /**
     * @var Message[]
     */
    private $messages;

    /**
     * @param string $output
     */
    public function __construct($output)
    {
        $this->parseOutput($output);
    }

    /**
     * @param string $output
     */
    private function parseOutput($output)
    {
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return \CornyPhoenix\Tex\Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
