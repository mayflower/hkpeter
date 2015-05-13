<?php

namespace elseym\HKPeterBundle\Controller;

use elseym\HKPeterBundle\Model\Key;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    public function fooAction(Request $request)
    {
        $keyString = "pub   2048R/E5C020D2 2013-02-14\nuid                  Simon Waibl <simon.waibl@mayflower.de>\nsub   2048R/3B4C66FF 2013-02-14\n";
        $key = Key::createFromString($keyString);

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

    public function addAction(Request $request)
    {
        $armoredKey = $request->request->get('armoredKey', null);
        if (null !== $armoredKey) {
            $key = $this->keyRepository->add($armoredKey);
            if ($key instanceof Key) {
                //TODO: add success to flashbag
            } else {
                //TODO: add error to flashbag
            }
        } else {
            //TODO: add error to flashbag
        }

        return new RedirectResponse($this->router->generate('hp_portal'));
    }
}
