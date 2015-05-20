<?php

namespace elseym\HKPeterBundle\KeyRepository;

use elseym\HKPeterBundle\Entity\GpgKey;
use elseym\HKPeterBundle\Factory\KeyFactory;
use elseym\HKPeterBundle\Model\Key;
use elseym\HKPeterBundle\Service\GnupgServiceInterface;

/**
 * Class GnupgRepository
 * @package elseym\HKPeterBundle\KeyRepository
 */
class GnupgKeyRepository implements KeyRepositoryInterface
{
    /** @var GnupgServiceInterface $gnupgService */
    protected $gnupgService;

    /** @var KeyFactory $keyFactory */
    private $keyFactory;


    /**
     * @param array $predicates
     * @param string $mode
     * @return GpgKey[]
     */
    public function findBy(array $predicates = [], $mode = self::FIND_PREDICATE_ALL)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * @param string $email
     * @return GpgKey|null
     */
    public function findByEmail($email)
    {
        // TODO: Implement findByEmail() method.
    }

    /**
     * @param string $keyId
     * @return GpgKey|null
     */
    public function findByKeyId($keyId)
    {
        // TODO: Implement findByKeyId() method.
    }

    /**
     * @param string $armoredKey
     * @return GpgKey[]
     */
    public function add($armoredKey)
    {
        return $this->keyFactory->createFromArmoredKey($armoredKey);
    }

    /**
     * @param GnupgServiceInterface $gnupgService
     * @return GnupgKeyRepository
     */
    public function setGnupgService(GnupgServiceInterface $gnupgService)
    {
        $this->gnupgService = $gnupgService;

        return $this;
    }

    /**
     * @param KeyFactory $keyFactory
     * @return GnupgKeyRepository
     */
    public function setKeyFactory($keyFactory)
    {
        $this->keyFactory = $keyFactory;

        return $this;
    }
}
