<?php

namespace elseym\HKPeterBundle\KeyRepository;

use elseym\HKPeterBundle\Exception\GnupgException;
use elseym\HKPeterBundle\Exception\KeyRepositoryException;
use elseym\HKPeterBundle\Model\Key;
use elseym\HKPeterBundle\Service\WordService;

/**
 * Class RandomKeyRepository
 * @package elseym\HKPeterBundle\KeyRepository
 */
class RandomKeyRepository implements KeyRepositoryInterface
{
    /** @var WordService $wordService */
    private $wordService;

    /**
     * @param array $predicates
     * @param string $mode
     * @return Key[]
     */
    public function findBy(array $predicates = [], $mode = self::FIND_PREDICATE_ALL)
    {
        foreach (['id', 'email', 'fingerprint', 'content'] as $key) {
            if (!isset($predicates[$key])) {
                $predicates[$key] = null;
            }
        }

        if (!isset($predicates['count'])) {
            $predicates['count'] = 1;
        }

        $keys = [];
        for ($i = 0; $i < $predicates['count']; ++$i) {
            $keys[] = $this->generateKey(
                $predicates['id'],
                $predicates['email'],
                $predicates['fingerprint'],
                $predicates['content']
            );
        }

        return $keys;
    }

    /**
     * @param string $armoredKey
     * @return Key[]
     */
    public function add($armoredKey)
    {
        throw new KeyRepositoryException('cannot add key to random key repository');
    }

    /**
     * @param null $id
     * @param null $email
     * @param null $fingerprint
     * @param null $content
     * @return Key
     */
    private function generateKey($id = null, $email = null, $fingerprint = null, $content = null)
    {
        return new Key(
            $id ?: mt_rand(41, 9742),
            $email ?: $this->wordService->get(1) . "@" . $this->wordService->get(1) . ".com",
            $fingerprint ?: sha1(mt_rand(41, 9742)),
            $content ?: implode(" ", $this->wordService->get(23))
        );
    }

    /**
     * @param WordService $wordService
     * @return $this
     */
    public function setWordService($wordService)
    {
        $this->wordService = $wordService;
        return $this;
    }
}
