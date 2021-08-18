<?php

namespace SecretServerBundle\SecretInDDD\Domain\VO;

use SecretServerBundle\SecretInDDD\Domain\Util\VO\ValueObjectInterface;
use SecretServerBundle\SecretInDDD\Domain\Exception\InvalidArgumentException;

class SecretRemainingViewsVO implements ValueObjectInterface
{
    /**
     * @var int $_remainingViews
     */
    private int $_remainingViews;

    /**
     * @param int $remainingViews
     *
     * @throws InvalidArgumentException
     */
    public function __construct(int $remainingViews)
    {
        $this->_checkBeforeChange($remainingViews);

        $this->_remainingViews = $remainingViews;
    }

    /**
     * @param int $remainingViews
     *
     * @throws InvalidArgumentException
     */
    private function _checkBeforeChange(int $remainingViews) : void
    {
        if (!preg_match('/^[1-9][0-9]{1,}$/', $remainingViews)) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @return int
     */
    private function getRemainingViews() : int
    {
        return $this->_remainingViews;
    }

    /**
     * @return int
     */
    public function getValue() : int
    {
        return $this->getRemainingViews();
    }
}
