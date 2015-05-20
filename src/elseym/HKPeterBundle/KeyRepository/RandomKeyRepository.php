<?php

namespace elseym\HKPeterBundle\KeyRepository;

use elseym\HKPeterBundle\Entity\GpgKey;
use elseym\HKPeterBundle\Entity\GpgKeyUserId;
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
     * @return GpgKey[]
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
     * @param string $email
     * @return GpgKey|null
     */
    public function findByEmail($email)
    {
        $result = $this->findBy(['email' => $email]);

        return $result ? $result[0] : null;
    }

    /**
     * @param string $keyId
     * @return GpgKey|null
     */
    public function findByKeyId($keyId)
    {
        $result = $this->findBy(['id' => $keyId]);

        return $result ? $result[0] : null;
    }

    /**
     * @param string $armoredKey
     * @return GpgKey[]
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
        $key = new GpgKey(Key::TYPE_PUB);
        $key->setKeyId($id ?: mt_rand(41, 9742));
        $key->setContent($content ?: implode(" ", $this->wordService->get(23)));
        $key->setFingerprint($fingerprint ?: sha1(mt_rand(41, 9742)));
        $userId = new GpgKeyUserId();
        $userId->setEmail($email ?: $this->wordService->get(1) . "@" . $this->wordService->get(1) . ".com");
        $userId->setKey($key);

        return $key;
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
