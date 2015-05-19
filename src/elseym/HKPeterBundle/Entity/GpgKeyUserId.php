<?php

namespace elseym\HKPeterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GpgKeyUserId
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="elseym\HKPeterBundle\Entity\GpgKeyUserIdRepository")
 */
class GpgKeyUserId
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var GpgKey
     *
     * @ORM\ManyToOne(targetEntity="elseym\HKPeterBundle\Entity\GpgKey", inversedBy="userIds")
     */
    private $key;

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
     * Set name
     *
     * @param string $name
     * @return GpgKeyUserId
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return GpgKeyUserId
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return GpgKeyUserId
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Get key
     *
     * @return GpgKey
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param GpgKey $key
     * @return GpgKey
     */
    public function setKey($key)
    {
        if (null !== $this->key) {
            $this->key->removeUserId($this);
        }
        $this->key = $key;
        if (null !== $this->key) {
            $this->key->addUserId($this);
        }

        return $this;
    }
}
