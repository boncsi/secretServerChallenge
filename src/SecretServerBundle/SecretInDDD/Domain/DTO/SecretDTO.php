<?php

namespace SecretServerBundle\SecretInDDD\Domain\DTO;

use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;
use DateTimeImmutable;

class SecretDTO implements SecretDTOInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var int
     */
    private $expiresAt;

    /**
     * @var int
     */
    private $remainingViews;

    /**
     * Get id
     *
     * @param int $id
     *
     * @return int
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

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
     * @return SecretDTO
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
     * @return SecretDTO
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
     * @param DateTimeImmutable $createdAt
     *
     * @return SecretDTO
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTimeImmutable
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
     * @return SecretDTO
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
     * @return SecretDTO
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

    public function serialize()
    {
        return serialize($this);
    }

    public function unserialize($serializedSecret)
    {
        return unserialize($serializedSecret);
    }
}
