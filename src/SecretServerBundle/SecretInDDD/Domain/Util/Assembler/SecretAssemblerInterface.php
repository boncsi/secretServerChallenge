<?php

namespace SecretServerBundle\SecretInDDD\Domain\Util\Assembler;

use SecretServerBundle\SecretInDDD\Domain\Util\Model\SecretModelInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;

interface SecretAssemblerInterface
{
    /**
     * Transform an Entity to a Data Transfer Object
     *
     * @param  SecretModelInterface $entity
     * @return mixed
     */
    public function transform(SecretModelInterface $entity);

    /**
     * Transform a Data Transfer Object to an Entity
     *
     * @param  SecretDTOInterface   $dto
     * @return mixed
     */
    public function reverseTransform(SecretDTOInterface $dto);
}
