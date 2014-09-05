<?php

namespace elseym\HKPeterBundle\Model;

/**
 * Class Key
 * @package elseym\HKPeterBundle\Model
 */
class Key
{
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
