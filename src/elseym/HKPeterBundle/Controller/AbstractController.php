<?php

namespace elseym\HKPeterBundle\Controller;

use elseym\HKPeterBundle\KeyRepository\KeyRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AbstractController
 * @package elseym\HKPeterBundle\Controller
 */
abstract class AbstractController
{
    const MESSAGE_TYPE_SUCCESS = "success";
    const MESSAGE_TYPE_INFO = "info";
    const MESSAGE_TYPE_ERROR = "error";

    /** @var KeyRepositoryInterface $keyRepository */
    protected $keyRepository;

    /** @var RouterInterface $router */
    protected $router;

    /** @var Session $session */
    protected $session;

    /** @var EngineInterface $templating */
    protected $templating;

    /**
     * @param $template
     * @param array $parameters
     * @param null $baseResponse
     * @return Response
     */
    protected function render($template, $parameters = [], $baseResponse = null)
    {
        return $this->templating->renderResponse(
            "elseymHKPeterBundle:" . $template . ".html.twig",
            $parameters,
            $baseResponse
        );
    }

    /**
     * @param string $message
     * @param string $type
     * @return $this
     */
    protected function addFlashMessage($message, $type = self::MESSAGE_TYPE_INFO)
    {
        $this->session->getFlashBag()->add($type, $message);

        return $this;
    }

    /**
     * @param string $type only consider messages of the given type or null for any type
     * @return bool
     */
    protected function hasFlashMessages($type = null)
    {
        if (null === $type) {
            return 0 < count($this->session->getFlashBag()->peekAll());
        }

        return $this->session->getFlashBag()->has($type);
    }

    /**
     * @param string $routeName
     * @param array $parameters
     * @return RedirectResponse
     */
    protected function redirect($routeName, $parameters = [])
    {
        return $this->redirectUrl($this->router->generate($routeName, $parameters));
    }

    /**
     * @param string $url
     * @return RedirectResponse
     */
    protected function redirectUrl($url)
    {
        return new RedirectResponse($url);
    }

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

    /**
     * @param Session $session
     * @return $this
     */
    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @param EngineInterface $templating
     * @return $this
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;

        return $this;
    }
}
