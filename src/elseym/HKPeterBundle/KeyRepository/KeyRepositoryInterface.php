<?php

namespace elseym\HKPeterBundle\KeyRepository;
use elseym\HKPeterBundle\Entity\GpgKey;
use elseym\HKPeterBundle\Model\Key;

/**
 * Interface KeyRepositoryInterface
 * @package elseym\HKPeterBundle\KeyRepository
 */
interface KeyRepositoryInterface
{
    // any of the given predicates
    const FIND_PREDICATE_ANY = "any";
    const FIND_PREDICATE_ALL = "all";

    /**
     * @param array $predicates
     * @param string $mode
     * @return Key[]
     */
    public function findBy(array $predicates = [], $mode = self::FIND_PREDICATE_ALL);

    /**
     * @param string $armoredKey
     * @return GpgKey[]
     */
    public function add($armoredKey);
}
