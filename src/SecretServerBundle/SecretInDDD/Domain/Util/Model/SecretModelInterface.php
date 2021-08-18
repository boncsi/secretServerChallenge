<?php

namespace SecretServerBundle\SecretInDDD\Domain\Util\Model;

interface SecretModelInterface
{
    public function getId();
    public function getHash();
    public function getSecret();
    public function getRemainingViews();
    public function getExpiresAt();
    public function getCreatedAt();

    public function setHash($hash);
    public function setSecret($secret);
    public function setRemainingViews($remainingViews);
    public function setExpiresAt($expiresAt);
    public function setCreatedAt($createdAt);
}
