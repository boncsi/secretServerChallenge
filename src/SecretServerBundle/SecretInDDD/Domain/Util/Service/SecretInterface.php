<?php

namespace SecretServerBundle\SecretInDDD\Domain\Util\Service;

use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;
use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;
use DateTimeImmutable;

interface SecretInterface
{
    public function getAll() : array;

    public function createNew(SecretVO $secretVO) : SecretDTOInterface;

    public function getByHash(string $hash) : SecretDTOInterface;

    public function getExpiresAtDateTime(SecretDTOInterface $secretDTO) : DateTimeImmutable;

    public function reduceRemainingViewsCount(SecretDTOInterface $secretDTO) : SecretDTOInterface;

    public function isExpired(SecretDTOInterface $secretDTO) : void;

    public function isViewable(SecretDTOInterface $secretDTO) : void;
}
