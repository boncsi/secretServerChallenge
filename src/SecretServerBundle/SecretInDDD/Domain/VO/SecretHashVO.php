<?php

namespace SecretServerBundle\SecretInDDD\Domain\VO;

use SecretServerBundle\SecretInDDD\Domain\Util\VO\ValueObjectInterface;
use SecretServerBundle\SecretInDDD\Domain\Exception\InvalidArgumentException;

class SecretHashVO implements ValueObjectInterface
{
    /**
     * @var ?string $_hash
     */
    private ?string $_hash;

    /**
     * @param string $hash
     *
     * @throws InvalidArgumentException
     */
    public function __construct(?string $hash)
    {
        $this->_checkBeforeChange($hash);

        $this->_hash = $hash;
    }

    /**
     * @param string $hash
     *
     * @throws InvalidArgumentException
     */
    private function _checkBeforeChange(?string $hash) : void
    {
        if (empty($hash) === FALSE && !preg_match('/^[0123456789\- áÁéÉűŰőŐúÚóÓüÜöÖA-Za-z\;\.\/]+$/', $hash)) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @return ?string
     */
    private function getHash() : ?string
    {
        return $this->_hash;
    }

    /**
     * @return ?string
     */
    public function getValue() : ?string
    {
        return $this->getHash();
    }
}
