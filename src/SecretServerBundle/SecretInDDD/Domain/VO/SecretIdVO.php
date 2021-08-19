<?php

namespace SecretServerBundle\SecretInDDD\Domain\VO;

use SecretServerBundle\SecretInDDD\Domain\Util\VO\ValueObjectInterface;
use SecretServerBundle\SecretInDDD\Domain\Exception\InvalidArgumentException;

class SecretIdVO implements ValueObjectInterface
{
    /**
     * @var ?int $_id
     */
    private ?int $_id = NULL;

    /**
     * @param ?int $id
     *
     * @throws InvalidArgumentException
     */
    public function __construct(?int $id)
    {
        $this->_checkBeforeChange($id);

        $this->_id = $id;
    }

    /**
     * @param int $id
     *
     * @throws InvalidArgumentException
     */
    private function _checkBeforeChange(?int $id) : void
    {
        if (empty($id) === FALSE && !preg_match('/^[1-9][0-9]{1,}$/', $id)) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @return ?int
     */
    private function getId() : ?int
    {
        return $this->_id;
    }

    /**
     * @return ?int
     */
    public function getValue() : ?int
    {
        return $this->getId();
    }
}
