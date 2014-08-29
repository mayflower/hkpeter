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
        return $this->templating->renderResponse("elseymHKPeterBundle:portal:index.html.twig");
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
