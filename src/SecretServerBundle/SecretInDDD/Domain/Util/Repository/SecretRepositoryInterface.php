<?php

namespace SecretServerBundle\SecretInDDD\Domain\Util\Repository;

use SecretServerBundle\SecretInDDD\Domain\Util\Model\SecretModelInterface;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;

interface SecretRepositoryInterface
{
    public function addNew(SecretVO $secretVO) : SecretModelInterface;

    public function save(SecretVO $secretVO) : SecretModelInterface;

    public function getSecretByHash(string $hash) : SecretModelInterface;

    public function getAllSecretItem() : array;
}
