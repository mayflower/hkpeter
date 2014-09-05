<?php

namespace elseym\HKPeterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PortalController
 * @package elseym\HKPeterBundle\Controller
 */
class PortalController extends AbstractController
{
    /** @var EngineInterface $templating */
    private $templating;

    /**
     * @param Request $request
     * @return Response
     */
    public function portalAction(Request $request)
    {
        $keys = $this->keyRepository->findBy(['count' => $request->get('count', 42)]);
        return $this->templating->renderResponse("elseymHKPeterBundle:portal:index.html.twig", ['keys' => $keys]);
    }

    /**
     * @param Request $request
     * @param $fingerprint
     * @return Response
     */
    public function getAction(Request $request, $fingerprint)
    {
        $keys = $this->keyRepository->findBy(['fingerprint' => $fingerprint]);
        return $this->templating->renderResponse("elseymHKPeterBundle:portal:get.html.twig", ['key' => reset($keys)]);
    }

    /**
     * @param EngineInterface $templating
     * @return $this
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
        return $this;
    }
}
