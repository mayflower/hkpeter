<?php

namespace elseym\HKPeterBundle\Exception;

/**
 * Class GnupgException
 * @package elseym\HKPeterBundle\Exception
 */
class GnupgException extends \LogicException
{
    /** @var string $command */
    private $command;

    /** @var int  */
    private $exitCode;

    /** @var array $output */
    private $output;

    /** @var string $returnValue */
    private $returnValue;

    /**
     * @param string $command the executed command string
     * @param int $exitCode the retuned exit code
     * @param array $output the complete stdout of the call
     * @param string $returnValue the last line of the output
     * @param \Exception $previous for chaining
     */
    function __construct($command, $exitCode, $output, $returnValue, \Exception $previous = null)
    {
        $this->command = $command;
        $this->exitCode = $exitCode;
        $this->output = $output;
        $this->returnValue = $returnValue;

        $message = sprintf(
            'GnuPG Exception while trying to run command "%s".' . PHP_EOL
            . 'The Command returned with exit code "%s" saying:' . PHP_EOL
            . '%s' . PHP_EOL,
            $command,
            $exitCode,
            $returnValue
        );

        parent::__construct($message, $exitCode, $previous);
    }

    /**
     * Returns the executed command string
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Returns the execution's exit code
     *
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Returns the complete stdout of the call
     *
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Returns the last line of the output
     *
     * @return string
     */
    public function getReturnValue()
    {
        return $this->returnValue;
    }
}
