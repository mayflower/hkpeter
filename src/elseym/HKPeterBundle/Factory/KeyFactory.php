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
    /**
     * @var GnupgCliService $gnupgService
     */
    private $gnupgService;

    /**
     * Import the armored key into the key store
     *
     * @param string $armoredKey
     * @return GpgKey[]
     */
    public function createFromArmoredKey($armoredKey)
    {
        //walk through all imported KeyIds
        //list key details
        //export key to get armored key

        $keyMatches = [];
        $gpgresult = $this->gnupgService->import($armoredKey);

        //first import row:
        //gpg: key 0E93FB4C: public key "Jens Broos <jens.broos@mayflower.de>" imported
        $regex = '/^gpg:\s+key\s+(?<keyId>[0-9a-f]{8}):\s+(?<keyType>[^"]+?)\s*"(?<userId>[^"]+?)"\s+(?<result>.+)$/im';
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

         *
         */
        $resultKey = null;
        $currentKey = null;
        $keyStringRows = explode(PHP_EOL, $keyString);
        foreach ($keyStringRows as $keyStringRow) {
            $keyStringCols = explode(':', $keyStringRow);
            $recordType = $keyStringCols[0];
            switch ($recordType) {
                case 'pub':
                    //create a new $currentKey for every new "pub" line
                    $currentKey = new GpgKey(Key::TYPE_PUB);
                    $keyId = $keyStringCols[4];
                    $armoredKey = $this->gnupgService->export($keyId);
                    $currentKey->setContent($armoredKey);

                    $resultKey = $currentKey;

                    $metaData = new GpgKeyMetadata();
                    $metaData->setBits(intval($keyStringCols[2]));
                    $metaData->setAlgorithm($keyStringCols[3]);
                    $metaData->setDateOfCreation(new \DateTime('@' . $keyStringCols[5]));
                    if (is_numeric($keyStringCols[6])) {
                        $metaData->setDateOfExpiration(new \DateTime('@' . $keyStringCols[6]));
                    }
                    $currentKey->setMetadata($metaData);

                    //TODO: save the capabilities in the meta data
                    $capabilities = strtolower($keyStringCols[7]);
                    $canEncrypt = stristr($capabilities, 'e');
                    $canSign = stristr($capabilities, 's');
                    $canCertify = stristr($capabilities, 'c');
                    $canAuthenticate = stristr($capabilities, 'a');

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
                    //TODO: split $keyStringCols[9] in userId parts
                    $userId = new GpgKeyUserId();
                    $userId->setComment($keyStringCols[9]);
                    $currentKey->addUserId($userId);
                    break;
                case 'uat':

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
    public function setGnupgService(GnupgCliService $gnupgService)
    {
        $this->gnupgService = $gnupgService;

        return $this;
    }
}
