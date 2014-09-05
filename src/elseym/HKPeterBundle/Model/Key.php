<?php

namespace elseym\HKPeterBundle\Model;

/**
 * Class Key
 * @package elseym\HKPeterBundle\Model
 */
class Key
{
    const KIND_PUB = 'pub';
    const KIND_SUB = 'sub';
    const KIND_SEC = 'sec';
    const KIND_SSB = 'ssb';

    /** @var string $id */
    private $id;

    /** @var string $email */
    private $email;

    /** @var string $fingerprint */
    private $fingerprint;

    /** @var string $content */
    private $content;

    /**
     * @param $id
     * @param $email
     * @param $fingerprint
     * @param $content
     */
    function __construct($id, $email, $fingerprint, $content)
    {
        $this->id = $id;
        $this->email = $email;
        $this->fingerprint = $fingerprint;
        $this->content = $content;
    }

    /**
     * @param $keyString
     */
    public static function createFromString($keyString)
    {
        $fields = [
            'uid' => [''],
            self::KIND_PUB => ['id', 'bits', 'algo', 'year', 'month', 'day', 'flag', 'flagyear', 'flagmonth', 'flagday']
        ];

        $fields[self::KIND_SUB] = $fields[self::KIND_PUB];
        $fields[self::KIND_SEC] = $fields[self::KIND_PUB];
        $fields[self::KIND_SSB] = $fields[self::KIND_PUB];

        $flagRegex = "(?:\\[(?'flag'(?:expire.:|revoked|expired|disabled))(?: (?'flagyear'\\d{4})-(?'flagmonth'\\d\\d)-(?'flagday'\\d\\d))\\])?";
        $metaRegex = "(?'bits'\\d+)(?'algo'\\S)\\/(?'id'[0-9A-Fa-f]+)(?!.*[<@>])";
        $nameRegex = "(?'uid'(?'name'.+?)\\<(?'email'[^@]+@.+\\.\\w+)\\>)";
        $kindRegex = "(?'kind'" . implode("|", array_keys($fields)) . ")";
        $pubSubRegex = "/" . $kindRegex . "\\s+(?:" . $metaRegex . "\\s+(?'year'\\d{4})-(?'month'\\d\\d)-(?'day'\\d\\d)\\s*" . $flagRegex ."|" . $nameRegex . ")/";

        $rawKeys = [];
        $keyIndex = 0;

        $keyLines = explode("\n", $keyString);
        foreach ($keyLines as $keyLine) {
            if (!preg_match($pubSubRegex, $keyLine, $matches)) {
                continue;
            }

            $data = self::extractFields($fields[$matches['kind']], $matches);
            switch ($matches['kind']) {
                case self::KIND_PUB:
                case self::KIND_SEC:
                    $keyIndex++;
                    $rawKeys[$keyIndex] = $data;
                    break;
                case self::KIND_SUB:
                case self::KIND_SSB:
                    $rawKeys[$keyIndex]['subs'][] = $data;
                    break;
                case "uid":
                    $rawKeys[$keyIndex]['uids'][] = $data;
                    break;
            }
        }

        var_dump($rawKeys);
    }

    /**
     * @param array $fields
     * @param array $source
     * @return array
     */
    private static function extractFields($fields, $source)
    {
        $destination = [];
        foreach($fields as $field) {
            if (isset($source[$field])) {
                $destination[$field] = $source[$field];
            }
        }

        return $destination;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * @param string $fingerprint
     * @return $this
     */
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->getContent();
    }
}
