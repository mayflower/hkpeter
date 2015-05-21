<?php

namespace elseym\HKPeterBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
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

    /** @var string $gnupgBaseDir */
    private $gnupgBaseDir;

    /** @var array $gnupgArgs */
    private $gnupgArgs = [];

    /** @var bool $sandboxed */
    private $sandboxed = true;

    /** @var Filesystem $fs */
    private $fs;

    /**
     * @param bool $sandboxed
     */
    function __construct($sandboxed = true)
    {
        $this->sandboxed = $sandboxed;
    }

    /**
     * removes the sandbox, if any
     */
    public function __destruct()
    {
        if ($this->sandboxed
            && $this->gnupgBaseDir !== $this->gnupgHomeDir
        ) {
            $this->fs->remove($this->gnupgHomeDir);
        }
    }

    /**
     * @param string $keyId
     * @return string
     */
    public function export($keyId)
    {
        return $this
            ->execute('--armor --export', $keyId)
            ->getOutput();
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
            ->getOutput();
    }

    /**
     * @param string $args
     * @param string $input
     * @return Process
     * @throws ProcessFailedException
     */
    private function execute($args = "", $input = null)
    {
        try {
            return (new Process(
                join(" ", [$this->gnupgBin, $this->gnupgArgs, "--homedir", escapeshellarg($this->gnupgHomeDir), $args]),
                $this->gnupgHomeDir,
                null,
                $input
            ))->mustRun();
        } catch (ProcessFailedException $e) {
            throw $e;
        }
    }

    /**
     * canonicalises the path and checks for existence
     *
     * @param string $path
     * @return string
     */
    private function resolvePath($path)
    {
        if (($name = realpath($path))
            && $this->fs->exists($name)
        ) {
            return $name;
        }

        throw new \RuntimeException("'$name' ('$path') is not a directory!");
    }

    /**
     * creates a directory with a random, unique name within the specified path
     *
     * @param string $path the absolute or relative path to where the directory should be created
     * @param string $prefix a string to prepend to the new directory name
     * @return string the full path to the newly created directory
     */
    private function createSandboxDir($path, $prefix = "")
    {
        $name = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $name .= uniqid($prefix);

        $this->fs->mkdir($name);
        if (!$this->fs->exists($name)) {
            throw new \RuntimeException("Could not write at path '$path'!");
        }

        return $name;
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
        $this->gnupgBaseDir = $this->resolvePath($gnupgHomeDir);
        $this->gnupgHomeDir = $this->gnupgBaseDir;

        if ($this->sandboxed) {
            $this->gnupgHomeDir = $this->createSandboxDir($this->gnupgBaseDir);
        }

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

    /**
     * @param Filesystem $filesystem
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->fs = $filesystem;

        return $this;
    }
}
