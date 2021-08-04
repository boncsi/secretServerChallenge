<?php

namespace SecretServerBundle\Util;

use Symfony\Component\HttpFoundation\Request;

interface SecretInterface
{
    public function getListItems();
    public function createNew(Request $request);
    public function getSecretByHash($hash);
}
