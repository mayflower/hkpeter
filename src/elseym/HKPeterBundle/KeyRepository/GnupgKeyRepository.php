<?php

namespace elseym\HKPeterBundle\KeyRepository;

use elseym\HKPeterBundle\Service\GnupgService;

/**
 * Class GnupgRepository
 * @package elseym\HKPeterBundle\KeyRepository
 */
class GnupgKeyRepository implements KeyRepositoryInterface
{
    /** @var GnupgService $gnupgService */
    protected $gnupgService;

    /**
     * @param GnupgService $gnupgService
     * @return $this
     */
    public function setGnupgService($gnupgService)
    {
        $this->gnupgService = $gnupgService;
        return $this;
    }

    public function findBy(array $predicates = [], $mode = self::FIND_PREDICATE_ALL)
    {
        // TODO: Implement findBy() method.
    }
}
