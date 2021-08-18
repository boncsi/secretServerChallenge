<?php

namespace SecretServerBundle\SecretInDDD\Domain\VO;

use SecretServerBundle\SecretInDDD\Domain\Util\VO\ValueObjectInterface;
use SecretServerBundle\SecretInDDD\Domain\Exception\InvalidArgumentException;

class SecretExpiresAtVO implements ValueObjectInterface
{
    /**
     * @var int $_expiresAt
     */
    private int $_expiresAt;

    /**
     * @param int $expiresAt
     *
     * @throws InvalidArgumentException
     */
    public function __construct(int $expiresAt)
    {
        $this->_checkBeforeChange($expiresAt);

        $this->_expiresAt = $expiresAt;
    }

    /**
     * @param int $expiresAt
     *
     * @throws InvalidArgumentException
     */
    private function _checkBeforeChange(int $expiresAt) : void
    {
        if (!preg_match('/^[1-9][0-9]{1,}$/', $expiresAt)) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @return int
     */
    private function getExpiresAt() : int
    {
        return $this->_expiresAt;
    }

    /**
     * @return int
     */
    public function getValue() : int
    {
        return $this->getExpiresAt();
    }
}
