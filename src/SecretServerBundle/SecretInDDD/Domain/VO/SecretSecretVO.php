<?php

namespace SecretServerBundle\SecretInDDD\Domain\VO;

use SecretServerBundle\SecretInDDD\Domain\Util\VO\ValueObjectInterface;
use SecretServerBundle\SecretInDDD\Domain\Exception\InvalidArgumentException;

class SecretSecretVO implements ValueObjectInterface
{
    /**
     * @var string $_secret
     */
    private string $_secret;

    /**
     * @param string $secret
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $secret)
    {
        $this->_checkBeforeChange($secret);

        $this->_secret = $secret;
    }

    /**
     * @param string $secret
     *
     * @throws InvalidArgumentException
     */
    private function _checkBeforeChange(string $secret) : void
    {
        if (!preg_match('/^[ áÁéÉűŰőŐúÚóÓüÜöÖA-Za-z\;\.\/]+$/', $secret)) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @return string
     */
    private function getSecret() : string
    {
        return $this->_secret;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->getSecret();
    }
}
