<?php

namespace SecretServerBundle\SecretInDDD\Application\Util\Service;

use Symfony\Component\HttpFoundation\Request;

interface SecretInterface
{
    public function getListItems() : array;

    public function createNew(Request $request) : ?array;

    //public function getSecretByHash(string $hash) : array;
}
