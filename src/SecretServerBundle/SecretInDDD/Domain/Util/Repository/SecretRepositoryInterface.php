<?php

namespace SecretServerBundle\SecretInDDD\Domain\Util\Repository;

use SecretServerBundle\SecretInDDD\Domain\Util\Model\SecretModelInterface;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;

interface SecretRepositoryInterface
{
    //TODO Boncsi, ezt még törölni kell vagy ellenőrizni, hogy kell-e
    /*public function findAll();

    public function find(SecretVO $vo);

    public function add(SecretModelInterface $entity);

    public function remove(SecretModelInterface $entity);*/

    public function addNew(SecretVO $secretVO) : SecretModelInterface;

    //public function getSecretByHash(string $hash) : SecretModelInterface;

    //public function reduceRemainingViewsCount(SecretModelInterface $secretEntity) : SecretModelInterface;

    //public function getAllSecretItem() : array;
}
