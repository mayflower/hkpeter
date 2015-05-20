<?php

namespace elseym\HKPeterBundle\Factory;

use elseym\HKPeterBundle\Entity\GpgKey;
use elseym\HKPeterBundle\Entity\GpgKeyMetadata;
use elseym\HKPeterBundle\Entity\GpgKeyUserId;
use elseym\HKPeterBundle\Model\Key;
use elseym\HKPeterBundle\Service\GnupgCliService;

/**
 * Class KeyFactory
 * @package elseym\HKPeterBundle\Factory
 */
class KeyFactory implements KeyFactoryInterface
{
    /** @var GnupgCliService $gnupgService */
    private $gnupgService;

    /**
     * @param string $armoredKey
     * @return GpgKey[]
     */
    public function createFromArmoredKey($armoredKey)
    {
        //import the armored key into the key store
        //walk through all imported KeyIds
        //list key details
        //export key to get armored key

        $keyMatches = [];
        $gpgresult = $this->gnupgService->import($armoredKey);
        //first import row:
        //gpg: key 0E93FB4C: public key "Jens Broos <jens.broos@mayflower.de>" imported
        $regex = '/^gpg:\s+key\s+(?<keyId>[0-9a-f]{8}):\s+(?<keyType>[^"]+?)?\s*"(?<userId>[^"]+?)"\s+(?<result>.+)$/im';
        if (0 >= preg_match_all($regex, $gpgresult, $keyMatches, PREG_SET_ORDER)) {
            throw new \RuntimeException("No keys found");
        }

        $regex = '/gpg: Total number processed: (?<count>\d+)/i';
        if (0 >= preg_match_all($regex, $gpgresult, $sumMatches)) {
            throw new \RuntimeException("gpg returned something weird!");
        }

        if (count($keyMatches) != $sumMatches["count"][0]) {
            throw new \RuntimeException("inconsistent data!");
        }

        $result = [];
        foreach ($keyMatches as $keyMatch) {
            //keyId, userId, result
            //TODO: check if the given keyId is already in the database
            $keyDetails = $this->gnupgService->listKeys($keyMatch['keyId']);
            $result[] = $this->createFromString($keyDetails);
        }

        return $result;
    }

    /**
     * @param string $keyId
     * @return GpgKey|null
     */
    public function createFromKeyId($keyId)
    {
        $keyDetails = $this->gnupgService->listKeys($keyId);
        return $this->createFromString($keyDetails);
    }

    /**
     * @param string $email
     * @return GpgKey|null
     */
    public function createFromEmail($email)
    {
        $keyDetails = $this->gnupgService->listKeys($email);
        return $this->createFromString($keyDetails);
    }

    /**
     * @param string $keyString
     * @return GpgKey|null
     */
    private function createFromString($keyString)
    {
        //The details of $keyString format are documented in the file doc/DETAILS, which is included in the GnuPG
        // source distribution.
        /**
         *
        tru::1:1431696160:0:3:1:5
        pub:-:4096:1:6ED27E9FFD204126:1431336318:::-:::scESC:
        fpr:::::::::FAD00D83DBC720A58168DABB6ED27E9FFD204126:
        uid:-::::1431336318::FB444581B249A0167680D4852E7DAB3386DEE22C::Marcel Idler <marcel.idler@mayflower.de>:
        sub:-:4096:1:A054F10708BD2CC8:1431336318::::::e:
        fpr:::::::::AC4E86A6D7E578C56CEF1513A054F10708BD2CC8:
         *
         */
        /*
         *
        tru::1:1432026330:0:3:1:5
        pub:-:2048:17:76D78F0500D026C4:1282220531:1534698479::-:::scESC:
        fpr:::::::::85E38F69046B44C1EC9FB07B76D78F0500D026C4:
        uid:-::::1421255279::CAACC8CE9116A0BE42E58C61602F127B194EF5A5::GPGTools Team <team@gpgtools.org>:
        uid:-::::1421255279::03B2DCE7652DBBB93DA77FFC4328F122656E20DD::GPGMail Project Team (Official OpenPGP Key) <gpgmail-devel@lists.gpgmail.org>:
        uid:-::::1421255279::8CACAFAD028BE38151D2361F9CD79CC81B4153B2::GPGTools Project Team (Official OpenPGP Key) <gpgtools-org@lists.gpgtools.org>:
        uat:-::::1421255279::076E59FC200B10E38AEEA745AB6547AEE99FB9EE::1 5890:
        sub:-:2048:16:07EAE49ADBCBE671:1282220531:1534698500:::::e:
        fpr:::::::::CF5DA29DD13D6856B5820B2F07EAE49ADBCBE671:
        sub:-:4096:1:E8A664480D9E43F5:1396950003:1704188403:::::s:
        fpr:::::::::8C31E5A17DD5D932B448FE1DE8A664480D9E43F5:
         *
         */
        $resultKey  = null;
        $currentKey = null;
        $keyStringRows = explode(PHP_EOL, $keyString);
        foreach ($keyStringRows as $keyStringRow) {
            $keyStringCols = explode(':', $keyStringRow);
            $recordType = $keyStringCols[0];
            switch ($recordType) {
                case 'pub':
                    //create a new $currentKey for every new "pub" line
                    $currentKey = new GpgKey(Key::TYPE_PUB);
                    $resultKey  = $currentKey;
                    $keyId = $keyStringCols[4];
                    $currentKey->setKeyId($keyId);
                    $armoredKey = $this->gnupgService->export($keyId);
                    $currentKey->setContent($armoredKey);

                    $metaData = new GpgKeyMetadata();
                    $metaData->setBits(intval($keyStringCols[2]));
                    $metaData->setAlgorithm($keyStringCols[3]);
                    $metaData->setDateOfCreation(new \DateTime('@' . $keyStringCols[5]));
                    if (is_numeric($keyStringCols[6])) {
                        $metaData->setDateOfExpiration(new \DateTime('@' . $keyStringCols[6]));
                    }
                    $capabilities = strtolower($keyStringCols[7]);
                    $metaData->setCanEncrypt(stristr($capabilities, 'e'));
                    $metaData->setCanSign(stristr($capabilities, 's'));
                    $metaData->setCanCertify(stristr($capabilities, 'c'));
                    $metaData->setCanAuthenticate(stristr($capabilities, 'a'));

                    $currentKey->setMetadata($metaData);
                    break;
                case 'sub':
                    if (null === $currentKey) {
                        throw new \RuntimeException('cant parse "sub" line without previous pub key line');
                    }
                    //take care that that subkey is only added to the real parent key and not to a subkey
                    $parentKey = $currentKey;
                    while ($parentKey->hasParentKey()) {
                        $parentKey = $parentKey->getParentKey();
                    }
                    $currentKey = new GpgKey(Key::TYPE_SUB);
                    $currentKey->setParentKey($parentKey);
                    break;
                case 'sec':
                case 'ssb':
                    throw new \RuntimeException('secret keys not supported');
                    break;
                case 'uid':
                    if (null === $currentKey) {
                        throw new \RuntimeException('cant parse "uid" line without previous pub key line');
                    }
                    $userParts = [];
                    if (1 === preg_match('/^(?<name>.*)\s+<(?<email>.*)>$/i', $keyStringCols[9], $userParts)) {
                        $userId = new GpgKeyUserId();
                        $userId->setName($userParts['name']);
                        $userId->setEmail($userParts['email']);
                        $userId->setKey($currentKey);
                    }
                    break;
                case 'uat':
                    /**
                     * A UAT record puts the attribute subpacket count here, a
                     * space, and then the total attribute subpacket size.
                     */
                    list($subpacketCount, $subpacketSize) = explode(' ', $keyStringCols[9], 2);

                    break;
                case 'fpr':
                    if (null === $currentKey) {
                        throw new \RuntimeException('cant parse "fpr" line without previous pub key line');
                    }
                    $currentKey->setFingerprint($keyStringCols[9]);
                    break;
                case 'tru':
                    continue;
            }
        }

        return $resultKey;
    }

    /**
     * @param GnupgCliService $gnupgService
     * @return $this;
     */
    public function setGnupgService($gnupgService)
    {
        $this->gnupgService = $gnupgService;

        return $this;
    }
}
