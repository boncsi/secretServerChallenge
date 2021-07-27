<?php

namespace SecretServerBundle\Service;

use SecretServerBundle\Entity\Secret;

/**
 * SecretService
 */
class SecretService
{
    /**
     * @var Secret
     */
    private $_secretEntity;

    /**
     * SecretService constructor.
     * @param Secret $secretEntity
     */
    public function __construct(Secret $secretEntity)
    {
        $this->_secretEntity = $secretEntity;
    }

    public function getFilledData()
    {
        return [
            'hash'           => $this->_secretEntity->getHash(),
            'secretText'     => $this->_secretEntity->getSecret(),
            'createdAt'      => $this->_secretEntity->getCreatedAt()->format('Y-m-d\TH-i-s.\0\0\0\Z'),
            'expiresAt'      => $this->getExpiresAtDateTime()->format('Y-m-d\TH-i-s.\0\0\0\Z'),
            'remainingViews' => $this->_secretEntity->getRemainingViews()
        ];
    }

    public function getExpiresAtDateTime()
    {
        $expiresAt = new \DateTime($this->_secretEntity->getCreatedAt()->format('Y-m-d H:i:s'));

        return $expiresAt->modify("+{$this->_secretEntity->getExpiresAt()} minutes");
    }
}
