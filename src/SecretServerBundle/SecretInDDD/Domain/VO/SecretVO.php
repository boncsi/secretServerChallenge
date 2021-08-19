<?php

namespace SecretServerBundle\SecretInDDD\Domain\VO;

use SecretServerBundle\SecretInDDD\Domain\Util\VO\ValueObjectInterface;
use SecretServerBundle\SecretInDDD\Domain\Exception\InvalidArgumentException;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretIdVO as SecretIdVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretHashVO as SecretHashVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretSecretVO as SecretSecretVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretRemainingViewsVO as SecretRemainingViewsVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretExpiresAtVO as SecretExpiresAtVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretCreatedAtVO as SecretCreatedAtVO;

class SecretVO implements ValueObjectInterface
{
    /**
     * @var ?SecretIdVO $_id
     */
    private ?SecretIdVO $_id;

    /**
     * @var SecretHashVO $_hash
     */
    private SecretHashVO $_hash;

    /**
     * @var SecretSecretVO $_secret
     */
    private SecretSecretVO $_secret;

    /**
     * @var SecretRemainingViewsVO $_remainingViews
     */
    private SecretRemainingViewsVO $_remainingViews;

    /**
     * @var SecretExpiresAtVO $_secretExpiresAt
     */
    private SecretExpiresAtVO $_secretExpiresAt;

    /**
     * @var SecretCreatedAtVO $_secretCreatedAt;
     */
    private SecretCreatedAtVO $_secretCreatedAt;

    /**
     * @param ?SecretIdVO $id
     * @param SecretHashVO $hash
     * @param SecretSecretVO $secret
     * @param SecretRemainingViewsVO $remainingViews
     * @param SecretExpiresAtVO $secretExpiresAt
     * @param SecretCreatedAtVO $secretCreatedAt
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        ?SecretIdVO $id,
        SecretHashVO $hash,
        SecretSecretVO $secret,
        SecretRemainingViewsVO $remainingViews,
        SecretExpiresAtVO $secretExpiresAt,
        SecretCreatedAtVO $secretCreatedAt
    ) {
        $this->_id              = $id;
        $this->_hash            = $hash;
        $this->_secret          = $secret;
        $this->_remainingViews  = $remainingViews;
        $this->_secretExpiresAt = $secretExpiresAt;
        $this->_secretCreatedAt = $secretCreatedAt;
    }

    /**
     * @return SecretIdVO
     */
    public function getId(): SecretIdVO
    {
        return $this->_id;
    }

    /**
     * @return SecretHashVO
     */
    public function getHash(): SecretHashVO
    {
        return $this->_hash;
    }

    /**
     * @return SecretSecretVO
     */
    public function getSecret(): SecretSecretVO
    {
        return $this->_secret;
    }

    /**
     * @return SecretRemainingViewsVO
     */
    public function getRemainingViews(): SecretRemainingViewsVO
    {
        return $this->_remainingViews;
    }

    /**
     * @return SecretExpiresAtVO
     */
    public function getSecretExpiresAt(): SecretExpiresAtVO
    {
        return $this->_secretExpiresAt;
    }

    /**
     * @return SecretCreatedAtVO
     */
    public function getSecretCreatedAt(): SecretCreatedAtVO
    {
        return $this->_secretCreatedAt;
    }

    /**
     * @return SecretVO
     */
    public function getValue() : SecretVO
    {
        return $this;
    }
}
