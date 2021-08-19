<?php

namespace SecretServerBundle\SecretInDDD\Domain\VO;

use DateTimeImmutable;
use SecretServerBundle\SecretInDDD\Domain\Util\VO\ValueObjectInterface;

class SecretCreatedAtVO implements ValueObjectInterface
{
    /**
     * @var DateTimeImmutable $_createdAt
     */
    private DateTimeImmutable $_createdAt;

    /**
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(DateTimeImmutable $createdAt)
    {
        $this->_checkBeforeChange($createdAt);

        $this->_createdAt = $createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     */
    private function _checkBeforeChange(DateTimeImmutable $createdAt) : void
    {
    }

    /**
     * @return DateTimeImmutable
     */
    private function getCreatedAt() : DateTimeImmutable
    {
        return $this->_createdAt;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->getCreatedAt()->format('Y-m-d H:i:s');
    }
}
