<?php

namespace elseym\HKPeterBundle\Model;

/**
 * Class Key
 * @package elseym\HKPeterBundle\Model
 */
abstract class Key
{
    const TYPE_PUB = 'pub';
    const TYPE_SUB = 'sub';
    const TYPE_SEC = 'sec';
    const TYPE_SSB = 'ssb';

    const ALGORITHM_RSA = 1;
    const ALGORITHM_ELGAMAL = 16;
    const ALGORITHM_DSA = 17;

    const VALIDITY_UNKNOWN_NEW = 'o';
    const VALIDITY_INVALID = 'i';
    const VALIDITY_DISABLED = 'd';
    const VALIDITY_REVOKED = 'r';
    const VALIDITY_EXPIRED = 'e';
    const VALIDITY_UNKNOWN = '-';
    const VALIDITY_UNDEFINED = 'q';
    const VALIDITY_VALID = 'n';
    const VALIDITY_VALID_MARGINAL = 'm';
    const VALIDITY_VALID_FULLY = 'f';
    const VALIDITY_VALID_ULTIMATELY = 'u';
}
