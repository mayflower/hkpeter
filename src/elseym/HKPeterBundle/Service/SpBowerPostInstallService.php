<?php

namespace elseym\HKPeterBundle\Service;

use Sp\BowerBundle\Bower\Event\BowerEvent;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class SpBowerPostInstallService
 * @package elseym\HKPeterBundle\Service
 */
class SpBowerPostInstallService
{
    /** @var string $outputDirectory */
    private $outputDirectory;

    /** @var Filesystem $filesystem */
    private $filesystem;

    /** @var array $assets */
    private $assets;

    /**
     * @param BowerEvent $event
     * @throws \Exception
     */
    public function copyAssets(BowerEvent $event)
    {
        $bowerAssetDirecory = $event->getConfiguration()->getAssetDirectory();

        foreach ($this->assets as $assetName => $paths) {
            echo " Copying assets for '" . $assetName . "'" . PHP_EOL;

            $inputFiles = glob($bowerAssetDirecory . DIRECTORY_SEPARATOR . $paths['input']);
            $outputDirectory = $this->outputDirectory . DIRECTORY_SEPARATOR . $paths['output'];
            $this->filesystem->mkdir($outputDirectory);

            foreach ($inputFiles as $inputFile) {
                $this->filesystem->copy(
                    $inputFile,
                    $outputDirectory . DIRECTORY_SEPARATOR . basename($inputFile),
                    true
                );
            }
        }

    }

    /**
     * @param string $outputDirectory
     * @return $this
     */
    public function setOutputDirectory($outputDirectory)
    {
        $this->outputDirectory = realpath($outputDirectory);
        return $this;
    }

    /**
     * @param Filesystem $filesystem
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    /**
     * @param array $assets
     * @return $this
     */
    public function setAssets(array $assets)
    {
        $this->assets = $assets;
        return $this;
    }
}
