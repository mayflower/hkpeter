<?php

namespace elseym\HKPeterBundle\Factory;


use elseym\HKPeterBundle\Entity\GpgKey;

interface KeyFactoryInterface
{
    /**
     * @param $armoredKey
     * @return GpgKey[]
     */
    public function createFromArmoredKey($armoredKey);
}
