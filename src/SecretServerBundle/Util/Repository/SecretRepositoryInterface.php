<?php

namespace SecretServerBundle\Util\Repository;

use Symfony\Component\HttpFoundation\Request;
use SecretServerBundle\Entity\Secret;

interface SecretRepositoryInterface
{
    public function addNew(array $secretPostData) : Secret;
    public function getSecretByHash(string $hash) : Secret;
    public function reduceRemainingViewsCount(Secret $secretEntity) : Secret;
    public function getAllSecretItem() : array;
}
