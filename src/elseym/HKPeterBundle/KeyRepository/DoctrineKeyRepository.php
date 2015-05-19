<?php

namespace elseym\HKPeterBundle\KeyRepository;

use Doctrine\ORM\EntityManager;
use elseym\HKPeterBundle\Factory\KeyFactory;
use elseym\HKPeterBundle\Model\Key;
use elseym\HKPeterBundle\Service\GnupgCliService;

/**
 * Class DoctrineKeyRepository
 * @package elseym\HKPeterBundle\KeyRepository
 */
class DoctrineKeyRepository implements KeyRepositoryInterface
{
    /** @var KeyFactory $keyFactory */
    private $keyFactory;

    /** @var EntityManager $entityManager */
    private $entityManager;

    /**
     * @param array $predicates
     * @param string $mode
     * @return Key[]
     */
    public function findBy(array $predicates = [], $mode = self::FIND_PREDICATE_ALL)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * @param string $armoredKey
     * @return GpgKey[]
     */
    public function add($armoredKey)
    {
        $keys = $this->keyFactory->createFromArmoredKey($armoredKey);

        //TODO: store the keys in the database

        return $keys;
    }

    /**
     * @param KeyFactory $keyFactory
     * @return DoctrineKeyRepository
     */
    public function setKeyFactory($keyFactory)
    {
        $this->keyFactory = $keyFactory;

        return $this;
    }

    /**
     * @param EntityManager $entityManager
     * @return DoctrineKeyRepository
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }
}
