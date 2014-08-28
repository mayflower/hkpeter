<?php

namespace elseym\HKPeterBundle\KeyRepository;

/**
 * Interface KeyRepositoryInterface
 * @package elseym\HKPeterBundle\KeyRepository
 */
interface KeyRepositoryInterface
{
    // any of the given predicates
    const FIND_PREDICATE_ANY = "any";
    const FIND_PREDICATE_ALL = "all";

    public function findBy(array $predicates = [], $mode = self::FIND_PREDICATE_ALL);
}
