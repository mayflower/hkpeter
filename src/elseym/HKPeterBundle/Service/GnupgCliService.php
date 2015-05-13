<?php

namespace elseym\HKPeterBundle\Service;

use elseym\HKPeterBundle\Exception\GnupgException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class GnupgCliService
 * @package elseym\HKPeterBundle\Service
 */
class GnupgCliService implements GnupgServiceInterface
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
     * @param string $armoredKey
     * @return string[]
     */
    public function import($armoredKey)
    {
        $command = $this->gnupgBin . ' ' . $this->gnupgArgs . ' --import';
        $proc = new Process($command, null, [], $armoredKey);
        try {
            $proc->mustRun();
        } catch (ProcessFailedException $e) {
            throw $e;
        }
        $output = $proc->getErrorOutput();
        //'/^gpg:\s+key\s+(?<id>[0-9a-f]{8}):\s+"(?<user>[^"]+?)"\s+(?<result>.+)$/i'
        //'/gpg: Total number processed: (?<count>\d+)/i'

        /*
         *
        gpg: key FD204126: "Marcel Idler <marcel.idler@mayflower.de>" not changed
        gpg: key A7F02194: "Marco Jantke (Work) <marco.jantke@mayflower.de>" not changed
        gpg: key 50E8118F: "Jonas Ernst <jonas.ernst@me.com>" not changed
        gpg: Total number processed: 3
        gpg:              unchanged: 3
         */

    }

    /**
     * @param string $fingerprint
     * @return string[]
     */
    public function listKeys($fingerprint)
    {

    }

    /**
     * @param $command
     * @param $args
     * @return bool
     */
    private function execute($command, $args)
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
     * @param array $gnupgArgs
     * @return $this
     */
    public function setGnupgArgs($gnupgArgs)
    {
        $this->gnupgArgs = $gnupgArgs;
        return $this;
    }
}
