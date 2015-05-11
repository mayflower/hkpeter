<?php

namespace elseym\HKPeterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var \stdClass
     *
     * @ORM\Column(name="metadata", type="object")
     */
    private $metadata;

    /**
     * @var array
     *
     * @ORM\Column(name="userIds", type="array")
     */
    private $userIds;

    /**
     * @var string
     *
     * @ORM\Column(name="fingerprint", type="string", length=255)
     */
    private $fingerprint;

    /**
     * @var array
     *
     * @ORM\Column(name="subKeys", type="array")
     */
    private $subKeys;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


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
     * Set metadata
     *
     * @param \stdClass $metadata
     * @return GpgKey
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return \stdClass 
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
}
