<?php

namespace elseym\HKPeterBundle\Controller;

use elseym\HKPeterBundle\KeyRepository\KeyRepositoryInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AbstractController
 * @package elseym\HKPeterBundle\Controller
 */
abstract class AbstractController
{
    /** @var KeyRepositoryInterface $keyRepository */
    protected $keyRepository;

    /** @var RouterInterface $router */
    protected $router;

    /**
     * @param KeyRepositoryInterface $keyRepository
     * @return $this
     */
    public function setKeyRepository(KeyRepositoryInterface $keyRepository)
    {
        $this->keyRepository = $keyRepository;

        return $this;
    }

    /**
     * @param RouterInterface $router
     * @return $this
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        return $this;
    }
}
