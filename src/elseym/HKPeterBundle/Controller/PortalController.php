<?php

namespace elseym\HKPeterBundle\Controller;

use elseym\HKPeterBundle\Factory\KeyFactoryInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PortalController
 * @package elseym\HKPeterBundle\Controller
 */
class PortalController extends AbstractController
{
    /** @var FormFactory $formFactory */
    private $formFactory;

    /** @var KeyFactoryInterface */
    private $keyFactory;

    /**
     * @param Request $request
     * @return Response
     */
    public function portalAction(Request $request)
    {
        $keys = $this->keyRepository->findBy();
        return $this->render("portal:index", ["keys" => $keys]);
    }

    /**
     * @param Request $request
     * @param string $fingerprint
     * @return Response
     * @throws NotFoundHttpException
     */
    public function getAction(Request $request, $fingerprint)
    {
        $keys = $this->keyRepository->findBy(["fingerprint" => $fingerprint]);
        if (1 > count($keys)) {
            throw new NotFoundHttpException("Key not found!");
        }

        return $this->render("portal:get", ["key" => $keys[0]]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $addForm = $this->formFactory->create('add_key');
        if ($request->isMethod('POST')
            && $addForm->handleRequest($request)->isValid()
        ) {
            $armoredKey = $addForm->get('armoredKey')->getData();
            if (1 > strlen($armoredKey)) {
                $this->addFlashMessage("Please submit some data.", self::MESSAGE_TYPE_ERROR);
            }

            $addedKeys = $this->keyRepository->add($armoredKey);
            $amount = count($addedKeys);
            if (1 > $amount) {
                $this->addFlashMessage("Please submit valid data.", self::MESSAGE_TYPE_ERROR);
            }

            if (!$this->hasFlashMessages(self::MESSAGE_TYPE_ERROR)) {
                $this->addFlashMessage(
                    "Successfully added $amount key" . ($amount != 1 ? "s" : "") . ".",
                    self::MESSAGE_TYPE_SUCCESS
                );

                return $this->redirect("hp_portal");
            }
        }

        return $this->render("portal:add", ['form' => $addForm->createView()]);
    }

    /**
     * @param FormFactory $formFactory
     * @return $this
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * @param KeyFactoryInterface $keyFactory
     * @return $this
     */
    public function setKeyFactory(KeyFactoryInterface $keyFactory)
    {
        $this->keyFactory = $keyFactory;

        return $this;
    }
}
