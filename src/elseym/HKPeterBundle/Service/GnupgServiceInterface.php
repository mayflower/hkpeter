<?php

namespace elseym\HKPeterBundle\Service;

/**
 * Interface GnupgServiceInterface
 * @package elseym\HKPeterBundle\Service
 */
interface GnupgServiceInterface
{
    /**
     * @param string $keyId
     * @return string
     */
    public function export($keyId);

    /**
     * @param string $armoredKey
     * @return string[]
     */
    public function import($armoredKey);

    /**
     * @param string $keyId
     * @return string[]
     */
    public function listKeys($keyId);
}
