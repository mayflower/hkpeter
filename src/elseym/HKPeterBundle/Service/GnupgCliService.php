<?php

namespace elseym\HKPeterBundle\Service;

use Symfony\Component\Finder\Finder;
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

    /** @var string $gnupgHomeDir */
    private $gnupgHomeDir;

    /** @var array $gnupgArgs */
    private $gnupgArgs = [];

    /** @var bool $implicitPurge */
    private $implicitPurge = true;

    /**
     * @param bool $implicitPurge
     */
    function __construct($implicitPurge = true)
    {
        $this->implicitPurge = $implicitPurge;
    }

    /**
     * @param string $keyId
     * @return string
     */
    public function export($keyId)
    {
        return $this
            ->execute('--armor --export', $keyId)
            ->getOutput()
        ;
    }

    /**
     * @param string $armoredKey
     * @return string
     */
    public function import($armoredKey)
    {
        return $this
            ->execute("--import", $armoredKey)
            ->getErrorOutput();
    }

    /**
     * executes the --list-keys with the given $keyId. Attention: $keyId can be a keyId OR an email address
     * @param string $keyId
     * @return string
     */
    public function listKeys($keyId)
    {
        /*
         * The double --with-fingerprint prints the fingerprint for the subkeys
         * too. --fixed-list-mode is the modern listing way printing dates in
         * seconds since Epoch and does not merge the first userID with the pub
         * record; gpg2 does this by default and the option is a dummy.
         */
        return $this
            ->execute(
                "--list-keys --with-colons --with-fingerprint --with-fingerprint --fixed-list-mode " .
                escapeshellarg($keyId)
            )
            ->getOutput()
        ;
    }

    /**
     * removes gpg databases from gpg homedir
     *
     * @return void
     */
    public function purge()
    {
        $gpgFiles = (new Finder())->files()->in($this->gnupgHomeDir)->name("*.gpg*");
        foreach ($gpgFiles as $pgpFile) {
            unlink($pgpFile->getPathname());
        }
    }

    /**
     * @param string $args
     * @param string $input
     * @return Process
     * @throws ProcessFailedException
     */
    private function execute($args = "", $input = null)
    {
        $proc = new Process(
            join(" ", [$this->gnupgBin, $this->gnupgArgs, $args])
        );

        if (null !== $input) {
            $proc->setInput($input);
        }

        try {
            return $proc->mustRun();
        } catch (ProcessFailedException $e) {
            throw $e;
        }
    }

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
     * @param $gnupgHomeDir
     * @return $this
     */
    public function setGnupgHomedir($gnupgHomeDir)
    {
        $homedir = realpath($gnupgHomeDir);
        if (false === $homedir || !is_dir($homedir)) {
            throw new \LogicException("'$homedir' ('$gnupgHomeDir') is not a directory!");
        }

        $this->gnupgHomeDir = $homedir;
        return $this;
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
