<?php

namespace elseym\HKPeterBundle\Controller;

use elseym\HKPeterBundle\Model\Key;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class PortalController
 * @package elseym\HKPeterBundle\Controller
 */
class PortalController extends AbstractController
{
    /** @var EngineInterface $templating */
    private $templating;

    /** @var Session $session */
    private $session;

    /** @var FormFactory $formFactory  */
    private $formFactory;

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
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $addForm = $this->formFactory->create('add_key');
        if ($request->isMethod('POST')) {
            $addForm->handleRequest($request);
            if ($addForm->isValid()) {
                $armoredKey = $addForm->get('armoredKey')->getData();
                if (null !== $armoredKey) {
                    $key = $this->keyRepository->add($armoredKey);
                    if ($key instanceof Key) {
                        $this->session->getFlashBag()->add('success', 'added key successfully');

                        return new RedirectResponse($this->router->generate('hp_portal'));
                    } else {
                        $this->session->getFlashBag()->add('error', 'invalid key');
                    }
                } else {
                    $this->session->getFlashBag()->add('error', 'no key given');
                }
            }
        }

        return $this->templating->renderResponse('elseymHKPeterBundle:portal:add.html.twig', array(
            'form' => $addForm->createView(),
        ));
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

    /**
     * @param FormFactory $formFactory
     * @return $this
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * @param Session $session
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }
}
