<?php

namespace elseym\HKPeterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use elseym\HKPeterBundle\Model\Key;

/**
 * GpgKey
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="elseym\HKPeterBundle\Entity\GpgKeyRepository")
 */
class GpgKey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="keyType", type="string", length=3)
     */
    private $keyType;

    /**
     * @var GpgKeyMetadata
     *
     * @ORM\OneToOne(targetEntity="elseym\HKPeterBundle\Entity\GpgKeyMetadata")
     */
    private $metadata;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="elseym\HKPeterBundle\Entity\GpgKeyUserId", mappedBy="key")
     */
    private $userIds;

    /**
     * @var string
     *
     * @ORM\Column(name="fingerprint", type="string", length=255)
     */
    private $fingerprint;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="elseym\HKPeterBundle\Entity\GpgKey", mappedBy="parentKey")
     */
    private $subKeys;

    /**
     * @var GpgKey
     *
     * @ORM\ManyToOne(targetEntity="elseym\HKPeterBundle\Entity\GpgKey", inversedBy="subKeys")
     */
    private $parentKey;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    function __construct($keyType)
    {
        $this->keyType = $keyType;
        $this->subKeys = new ArrayCollection();
        $this->userIds = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get keyType
     *
     * @return string
     */
    public function getKeyType()
    {
        return $this->keyType;
    }

    /**
     * Set keyType
     *
     * @param string $keyType
     * @return GpgKey
     */
    public function setKeyType($keyType)
    {
        $this->keyType = $keyType;

        return $this;
    }

    /**
     * Set metadata
     *
     * @param GpgKeyMetadata $metadata
     * @return GpgKey
     */
    public function setMetadata(GpgKeyMetadata $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return GpgKeyMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set userIds
     *
     * @param array $userIds
     * @return GpgKey
     */
    public function setUserIds($userIds)
    {
        $this->userIds = $userIds;

        return $this;
    }

    /**
     * Get userIds
     *
     * @return array
     */
    public function getUserIds()
    {
        return $this->userIds;
    }

    /**
     * Add userId
     *
     * @param GpgKeyUserId $userId
     * @return $this
     */
    public function addUserId(GpgKeyUserId $userId)
    {
        $this->userIds->add($userId);

        return $this;
    }

    public function removeUserId(GpgKeyUserId $userId)
    {
        $this->userIds->removeElement($userId);

        return $this;
    }

    /**
     * Set fingerprint
     *
     * @param string $fingerprint
     * @return GpgKey
     */
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;

        return $this;
    }

    /**
     * Get fingerprint
     *
     * @return string
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * Set subKeys
     *
     * @param array $subKeys
     * @return GpgKey
     */
    public function setSubKeys($subKeys)
    {
        $this->subKeys = $subKeys;

        return $this;
    }

    /**
     * Get subKeys
     *
     * @return array
     */
    public function getSubKeys()
    {
        return $this->subKeys;
    }

    public function addSubKey(GpgKey $subKey)
    {
        if (Key::TYPE_PUB !== $this->keyType) {
            throw new \RuntimeException('cant add subKey to a GpgKey with other type than "' . Key::TYPE_PUB . '"');
        }
        $this->subKeys->add($subKey);

        return $this;
    }

    public function removeSubKey(GpgKey $subKey)
    {
        $this->subKeys->removeElement($subKey);
    }

    /**
     * Set content
     *
     * @param string $content
     * @return GpgKey
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return GpgKey
     */
    public function getParentKey()
    {
        return $this->parentKey;
    }

    /**
     * @param GpgKey $parentKey
     * @return GpgKey
     */
    public function setParentKey($parentKey)
    {
        if (Key::TYPE_PUB === $this->keyType) {
            throw new \RuntimeException('cant set parentKey of a GpgKey with type "' . Key::TYPE_PUB . '"');
        }
        if (null !== $this->parentKey) {
            $this->parentKey->removeSubKey($this);
        }
        $this->parentKey = $parentKey;
        if (null !== $this->parentKey) {
            $this->parentKey->addSubKey($this);
        }

        return $this;
    }

    public function hasParentKey()
    {
        return (null !== $this->getParentKey());
    }
}
