<?php

namespace elseym\HKPeterBundle\Service;

/**
 * Interface GnupgServiceInterface
 * @package elseym\HKPeterBundle\Service
 */
interface GnupgServiceInterface
{
    /**
     * @param string $armoredKey
     * @return string[]
     */
    public function import($armoredKey);

    /**
     * @param string $fingerprint
     * @return string[]
     */
    public function listKeys($fingerprint);
}
