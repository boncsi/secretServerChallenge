<?php

namespace SecretServerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Secret
 *
 * @ORM\Table(name="secret")
 * @ORM\Entity(repositoryClass="SecretServerBundle\Repository\SecretRepository")
 */
class Secret
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="text")
     * @Assert\NotBlank
     */
    private $secret;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     * @Assert\NotBlank
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="expiresAt", type="bigint", columnDefinition="BIGINT(32) NOT NULL")
     * @Assert\NotBlank
     */
    private $expiresAt;

    /**
     * @var int
     *
     * @ORM\Column(name="remainingViews", type="bigint", columnDefinition="BIGINT(32) NOT NULL")
     * @Assert\NotBlank
     */
    private $remainingViews;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Secret
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set secret
     *
     * @param string $secret
     *
     * @return Secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Secret
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set expiresAt
     *
     * @param integer $expiresAt
     *
     * @return Secret
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return int
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set remainingViews
     *
     * @param integer $remainingViews
     *
     * @return Secret
     */
    public function setRemainingViews($remainingViews)
    {
        $this->remainingViews = $remainingViews;

        return $this;
    }

    /**
     * Get remainingViews
     *
     * @return int
     */
    public function getRemainingViews()
    {
        return $this->remainingViews;
    }
}

