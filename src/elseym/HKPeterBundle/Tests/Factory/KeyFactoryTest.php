<?php
use elseym\HKPeterBundle\Entity\GpgKey;
use elseym\HKPeterBundle\Factory\KeyFactory;
use elseym\HKPeterBundle\Service\GnupgCliService;

/**
 * Class KeyFactoryTest
 *
 * @covers KeyFactory
 */
class KeyFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \AppKernel
     */
    private $kernel;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @var KeyFactory
     */
    private $model;

    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer();

        $this->model = new KeyFactory();
    }

    /**
     * @return string
     */
    private function getArmoredKey()
    {
        return file_get_contents(__DIR__ . '/armoredKey.txt');
    }

    /**
     * @return string
     */
    private function getGnupgServiceImportResultImported()
    {
        return file_get_contents(__DIR__ . '/gnupgImportResultImported.txt');
    }

    /**
     * @return string
     */
    private function getGnupgServiceImportResultUnchanged()
    {
        return file_get_contents(__DIR__ . '/gnupgImportResultUnchanged.txt');
    }

    /**
     * @return string
     */
    private function getGnupgServiceListKeysResult()
    {
        return file_get_contents(__DIR__ . '/gnupgListKeysResult.txt');
    }

    private function getGnupgServiceExportResult()
    {
        return file_get_contents(__DIR__ . '/gnupgExportResult.txt');
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|GnupgCliService
     */
    private function getGnupgServiceMock()
    {
        return $this->getMock(
            'elseym\HKPeterBundle\Service\GnupgCliService',
            ['import', 'listKeys', 'export'],
            [],
            'GnupgCliServiceMock',
            true,
            false,
            false
        );
    }

    /**
     * @covers KeyFactory::createFromArmoredKey
     */
    public function mops()
    {
        /**
        $mock = $this->getGnupgServiceMock();
        $mock->expects($this->once())
             ->method('import')
             ->willReturn($this->getGnupgServiceImportResult());*/

        $service = $this->container->get('hkpeter.service.gnupg_cli');
        echo "LIST: ";
var_dump($service->listKeys('FD204126'));
        echo " ENDLIST;";
        #$this->model->setGnupgService($service);
        #$keys = $this->model->createFromArmoredKey($this->getArmoredKey());
    }

    /**
     * @covers KeyFactory::createFromArmoredKey
     * @expectedException RuntimeException
     * @expectedExceptionMessage No keys found
     */
    public function testCreateFromArmoredKeyThrowsRuntimeExceptionWithNoKeysFound()
    {
        $mock = $this->getGnupgServiceMock();
        $mock->expects($this->once())
             ->method('import')
             ->willReturn('no keys here');

        $this->model->setGnupgService($mock);
        $this->model->createFromArmoredKey($this->getArmoredKey());
    }

    /**
     * @covers KeyFactory::createFromArmoredKey
     */
    public function testCreateFromArmoredKeyWithNewImportedKey()
    {
        $mock = $this->getGnupgServiceMock();
        $mock->expects($this->once())
            ->method('import')
            ->willReturn($this->getGnupgServiceImportResultImported());
        $mock->expects($this->once())
             ->method('listKeys')
             ->willReturn($this->getGnupgServiceListKeysResult());
        $mock->expects($this->once())
             ->method('export')
             ->willReturn($this->getGnupgServiceExportResult());

        $this->model->setGnupgService($mock);
        $keys = $this->model->createFromArmoredKey($this->getArmoredKey());

        /** @var GpgKey $key */
        foreach($keys as $key) {
            $this->assertSame($this->getGnupgServiceExportResult(), $key->getContent());
        }
    }

    /**
     * @covers KeyFactory::createFromArmoredKey
     */
    public function testCreateFromArmoredKeyWithUnchangedKey()
    {
        $mock = $this->getGnupgServiceMock();
        $mock->expects($this->once())
            ->method('import')
            ->willReturn($this->getGnupgServiceImportResultUnchanged());
        $mock->expects($this->once())
            ->method('listKeys')
            ->willReturn($this->getGnupgServiceListKeysResult());
        $mock->expects($this->once())
            ->method('export')
            ->willReturn($this->getGnupgServiceExportResult());

        $this->model->setGnupgService($mock);
        $keys = $this->model->createFromArmoredKey($this->getArmoredKey());

        /** @var GpgKey $key */
        foreach($keys as $key) {
            $this->assertSame($this->getGnupgServiceExportResult(), $key->getContent());
        }
    }

}