<?php

namespace SecretServerBundle\SecretInDDD\Domain\Assembler;

use SecretServerBundle\SecretInDDD\Domain\Util\Assembler\SecretAssemblerInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\Model\SecretModelInterface;

use SecretServerBundle\SecretInDDD\Domain\DTO\SecretDTO;
use SecretServerBundle\Entity\Secret;

class SecretAssembler implements SecretAssemblerInterface
{
    /**
     * Transform an Entity to a Data Transfer Object
     *
     * @param  SecretModelInterface $secretEntity
     * @return SecretDTOInterface
     */
    public function transform(SecretModelInterface $secretEntity) : SecretDTOInterface
    {
        $secretDTO = new SecretDTO();

        $secretDTO->setId($secretEntity->getId());
        $secretDTO->setHash($secretEntity->getHash());
        $secretDTO->setSecret($secretEntity->getSecret());
        $secretDTO->setRemainingViews($secretEntity->getRemainingViews());
        $secretDTO->setExpiresAt($secretEntity->getExpiresAt());
        $secretDTO->setCreatedAt($secretEntity->getCreatedAt());

        return $secretDTO;
    }

    /**
     * Transform a Data Transfer Object to an Entity
     *
     * @param  SecretDTOInterface   $secretDTO
     * @return SecretModelInterface
     */
    public function reverseTransform(SecretDTOInterface $secretDTO) : SecretModelInterface
    {
        $secretEntity = new Secret();

        $secretEntity->setId($secretDTO->getId());
        $secretEntity->setHash($secretDTO->getHash());
        $secretEntity->setSecret($secretDTO->getSecret());
        $secretEntity->setRemainingViews($secretDTO->getRemainingViews());
        $secretEntity->setExpiresAt($secretDTO->getExpiresAt());
        $secretEntity->setCreatedAt($secretDTO->getCreatedAt());

        return $secretEntity;
    }

    /**
     * Transform an DTO to an array
     *
     * @param  SecretDTOInterface $secretFormattedAndFilledDTO
     * @return array
     */
    public function transformDTOToArray(SecretDTOInterface $secretFormattedAndFilledDTO) : array
    {
        return [
            'hash'           => $secretFormattedAndFilledDTO->getHash(),
            'secretText'     => $secretFormattedAndFilledDTO->getSecret(),
            'createdAt'      => $secretFormattedAndFilledDTO->getCreatedAt(),
            'expiresAt'      => $secretFormattedAndFilledDTO->getExpiresAt(),
            'remainingViews' => $secretFormattedAndFilledDTO->getRemainingViews()
        ];
    }
}
