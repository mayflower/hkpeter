<?php

namespace elseym\HKPeterBundle\Service;

use elseym\HKPeterBundle\Exception\GnupgException;

/**
 * Class GnupgService
 * @package elseym\HKPeterBundle\Service
 */
class GnupgService
{
    /** @var string $gnupgBin */
    private $gnupgBin;

    /** @var array $gnupgArgs */
    private $gnupgArgs = [];

    /**
     * @param string $gnupgBin
     * @return $this
     */
    public function setGnupgBin($gnupgBin)
    {
        $this->gnupgBin = $gnupgBin;
        return $this;
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getGnupgArg($name, $default = null)
    {
        if ($this->hasGnupgArg($name)) {
            return $this->gnupgArgs[$name];
        }
        return $default;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasGnupgArg($name)
    {
        return isset($this->gnupgArgs[$name]);
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setGnupgArg($name, $value)
    {
        $this->gnupgArgs[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function unsetGnupgArg($name)
    {
        if ($this->hasGnupgArg($name)) {
            unset($this->gnupgArgs[$name]);
        }
        return $this;
    }

    /**
     * @param $command
     * @param $args
     * @return bool
     */
    public function execute($command, $args)
    {
        $gnupgArgs = $this->buildArgsString($args);
        $gnupgCmd = $this->gnupgBin . ' ' . $gnupgArgs . ' ' . $command;

        $exitCode = 0;
        $output = [];
        $out = exec($gnupgCmd, $output, $exitCode);

        if (0 !== $exitCode) {
            throw new GnupgException($gnupgCmd, $exitCode, $output, $out);
        }

        return $output;
    }

    /**
     * @param array $moreArgs
     * @return string
     */
    private function buildArgsString($moreArgs = [])
    {
        $gnupgArgs = $this->getGnupgArgs();
        $gnupgArgs = array_merge($gnupgArgs, $moreArgs);

        $arguments = [];

        foreach ($gnupgArgs as $name => $value) {
            if (strlen($name) === 1) {
                $name = '-' . $name;
            } elseif (strlen($name) > 1) {
                $name = '--' . $name;
            }
            $arguments[] = trim($name . ' ' . escapeshellarg($value));
        }

        return implode(' ', $arguments);
    }

    /**
     * @return array
     */
    public function getGnupgArgs()
    {
        return $this->gnupgArgs;
    }

    /**
     * @param array $gnupgArgs
     * @return $this
     */
    public function setGnupgArgs($gnupgArgs)
    {
        $this->gnupgArgs = $gnupgArgs;
        return $this;
    }
}
