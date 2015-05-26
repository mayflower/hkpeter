<?php

namespace elseym\HKPeterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GpgKeyMetadata
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="elseym\HKPeterBundle\Entity\GpgKeyMetadataRepository")
 */
class GpgKeyMetadata
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
     * @var integer
     *
     * @ORM\Column(name="bits", type="integer")
     */
    private $bits;

    /**
     * @var integer
     *
     * @ORM\Column(name="algorithm", type="integer")
     */
    private $algorithm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfExpiration", type="datetime", nullable=true)
     */
    private $dateOfExpiration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfCreation", type="datetime")
     */
    private $dateOfCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfRevocation", type="datetime", nullable=true)
     */
    private $dateOfRevocation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfDisablement", type="datetime", nullable=true)
     */
    private $dateOfDisablement;

    /**
     * @var bool
     *
     * @ORM\Column(name="canEncrypt", type="boolean")
     */
    private $canEncrypt;

    /**
     * @var bool
     *
     * @ORM\Column(name="canSign", type="boolean")
     */
    private $canSign;

    /**
     * @var bool
     *
     * @ORM\Column(name="canCertify", type="boolean")
     */
    private $canCertify;

    /**
     * @var bool
     *
     * @ORM\Column(name="canAuthenticate", type="boolean")
     */
    private $canAuthenticate;

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
     * Set bits
     *
     * @param integer $bits
     * @return GpgKeyMetadata
     */
    public function setBits($bits)
    {
        $this->bits = $bits;

        return $this;
    }

    /**
     * Get bits
     *
     * @return integer
     */
    public function getBits()
    {
        return $this->bits;
    }

    /**
     * Set algorithm
     *
     * @param integer $algorithm
     * @return GpgKeyMetadata
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * Get algorithm
     *
     * @return integer
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Set dateOfExpiration
     *
     * @param \DateTime $dateOfExpiration
     * @return GpgKeyMetadata
     */
    public function setDateOfExpiration($dateOfExpiration)
    {
        $this->dateOfExpiration = $dateOfExpiration;

        return $this;
    }

    /**
     * Get dateOfExpiration
     *
     * @return \DateTime
     */
    public function getDateOfExpiration()
    {
        return $this->dateOfExpiration;
    }

    /**
     * Set dateOfCreation
     *
     * @param \DateTime $dateOfCreation
     * @return GpgKeyMetadata
     */
    public function setDateOfCreation($dateOfCreation)
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    /**
     * Get dateOfCreation
     *
     * @return \DateTime
     */
    public function getDateOfCreation()
    {
        return $this->dateOfCreation;
    }

    /**
     * Set dateOfRevocation
     *
     * @param \DateTime $dateOfRevocation
     * @return GpgKeyMetadata
     */
    public function setDateOfRevocation($dateOfRevocation)
    {
        $this->dateOfRevocation = $dateOfRevocation;

        return $this;
    }

    /**
     * Get dateOfRevocation
     *
     * @return \DateTime
     */
    public function getDateOfRevocation()
    {
        return $this->dateOfRevocation;
    }

    /**
     * Set dateOfDisablement
     *
     * @param \DateTime $dateOfDisablement
     * @return GpgKeyMetadata
     */
    public function setDateOfDisablement($dateOfDisablement)
    {
        $this->dateOfDisablement = $dateOfDisablement;

        return $this;
    }

    /**
     * Get dateOfDisablement
     *
     * @return \DateTime
     */
    public function getDateOfDisablement()
    {
        return $this->dateOfDisablement;
    }

    /**
     * @return boolean
     */
    public function isCanEncrypt()
    {
        return $this->canEncrypt;
    }

    /**
     * @param boolean $canEncrypt
     * @return GpgKeyMetadata
     */
    public function setCanEncrypt($canEncrypt)
    {
        $this->canEncrypt = $canEncrypt;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCanSign()
    {
        return $this->canSign;
    }

    /**
     * @param boolean $canSign
     * @return GpgKeyMetadata
     */
    public function setCanSign($canSign)
    {
        $this->canSign = $canSign;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCanCertify()
    {
        return $this->canCertify;
    }

    /**
     * @param boolean $canCertify
     * @return GpgKeyMetadata
     */
    public function setCanCertify($canCertify)
    {
        $this->canCertify = $canCertify;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCanAuthenticate()
    {
        return $this->canAuthenticate;
    }

    /**
     * @param boolean $canAuthenticate
     * @return GpgKeyMetadata
     */
    public function setCanAuthenticate($canAuthenticate)
    {
        $this->canAuthenticate = $canAuthenticate;

        return $this;
    }
}
