<?php

namespace elseym\HKPServerBundle\Controller;

use elseym\HKPeterBundle\KeyRepository\KeyRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class KeyServerController extends Controller
{

    public function lookupAction(Request $request)
    {
        $operation = $request->get('op');
        $search = $request->get('search');

    }

    /**
     * @return KeyRepositoryInterface
     */
    private function getKeyRepository()
    {
        return $this->get('hkpeter.key_repository.gnupg');
    }
}
