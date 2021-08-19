<?php

namespace SecretServerBundle\SecretInDDD\Domain\DTO;

use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;

class SecretFormattedAndFilledDTO implements SecretDTOInterface
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
     * @var string
     */
    private $createdAt;

    /**
     * @var string
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
     * @return SecretFormattedAndFilledDTO
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
     * @return SecretFormattedAndFilledDTO
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
     * @param string $createdAt
     *
     * @return SecretFormattedAndFilledDTO
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set expiresAt
     *
     * @param string $expiresAt
     *
     * @return SecretFormattedAndFilledDTO
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return string
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
     * @return SecretFormattedAndFilledDTO
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
