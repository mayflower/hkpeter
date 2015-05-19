<?php

namespace elseym\HKPeterBundle\Factory;


interface KeyFactoryInterface
{
    /**
     * @param $armoredKey
     * @return Key[]
     */
    public function createFromArmoredKey($armoredKey);
}
