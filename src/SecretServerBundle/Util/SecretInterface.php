<?php

namespace SecretServerBundle\Util;

interface SecretInterface
{
    public function addNew(array $post);
    public function getSecretByHash($hash);
    public function getAllSecretItem();
}
