<?php

namespace elseym\HKPeterBundle\Controller;

use elseym\HKPeterBundle\KeyRepository\KeyRepositoryInterface;

/**
 * Class AbstractController
 * @package elseym\HKPeterBundle\Controller
 */
abstract class AbstractController
{
    /** @var KeyRepositoryInterface $keyRepository */
    protected $keyRepository;

    /**
     * @param KeyRepositoryInterface $keyRepository
     * @return $this
     */
    public function setKeyRepository(KeyRepositoryInterface $keyRepository)
    {
        $this->keyRepository = $keyRepository;
        return $this;
    }
}
