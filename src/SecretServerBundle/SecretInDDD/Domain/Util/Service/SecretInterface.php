<?php

namespace SecretServerBundle\SecretInDDD\Domain\Util\Service;

use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;
use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;

interface SecretInterface
{
    //public function getListItems() : array;

    public function createNew(SecretVO $secretVO) : SecretDTOInterface;

    //public function getSecretByHash(string $hash) : array;
}
