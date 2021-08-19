<?php

namespace SecretServerBundle\SecretInDDD\Domain\Util\VO;

/**
 * Interface ValueObject
 */
interface ValueObjectInterface
{
    /**
     * Return the value of the ValueObject. The value *MUST* be immutable
     *
     * @return mixed
     */
    public function getValue();
}
