<?php

namespace elseym\HKPeterBundle\KeyRepository;

use elseym\HKPeterBundle\Model\Key;
use elseym\HKPeterBundle\Service\GnupgServiceInterface;

/**
 * Class GnupgRepository
 * @package elseym\HKPeterBundle\KeyRepository
 */
class GnupgKeyRepository implements KeyRepositoryInterface
{
    /** @var GnupgServiceInterface $gnupgService */
    protected $gnupgService;

    /**
     * @param GnupgServiceInterface $gnupgService
     * @return $this
     */
    public function setGnupgService(GnupgServiceInterface $gnupgService)
    {
        $this->gnupgService = $gnupgService;
        return $this;
    }

    public function findBy(array $predicates = [], $mode = self::FIND_PREDICATE_ALL)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * @param string $armoredKey
     * @return Key
     */
    public function add($armoredKey)
    {
        $keyMatches = [];
        $gpgresult = $this->gnupgService->import($armoredKey);

        $regex = '/^gpg:\s+key\s+(?<keyId>[0-9a-f]{8}):\s+"(?<userId>[^"]+?)"\s+(?<result>.+)$/i';
        if (0 >= preg_match_all($regex, $gpgresult, $keyMatches)) {
            throw new \RuntimeException("no keys found!");
        }

        $regex = '/gpg: Total number processed: (?<count>\d+)/i';
        if (0 >= preg_match_all($regex, $gpgresult, $sumMatches)) {
            throw new \RuntimeException("gpg returned something weird!");
        }

        if (count($keyMatches) != $sumMatches[0]["count"]) {
            throw new \RuntimeException("inconsistent data!");
        }

        // do more stuff!
    }
}
