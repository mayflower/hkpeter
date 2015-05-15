<?php

namespace elseym\HKPeterBundle\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class GpgDumpService
 * @package elseym\HKPeterBundle\Service
 */
class PgpdumpCliService
{
    private $pgpdumpBin;

    public function dumpKey($armoredKey)
    {
        if (null === $armoredKey) {
            throw new \InvalidArgumentException('$armoredKey must not be null');
        }
        $process = new Process(
            join(' ', [$this->pgpdumpBin])
        );
        $process->setInput($armoredKey);

        try {
            return $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw $e;
        }
    }

    /**
     * @param mixed $pgpdumpBin
     */
    public function setPgpdumpBin($pgpdumpBin)
    {
        $this->pgpdumpBin = $pgpdumpBin;
    }
}
