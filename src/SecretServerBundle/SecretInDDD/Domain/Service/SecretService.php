<?php

namespace SecretServerBundle\SecretInDDD\Domain\Service;

use SecretServerBundle\Repository\SecretRepository;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretExpiresAtVO as SecretExpiresAtVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretIdVO as SecretIdVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretRemainingViewsVO as SecretRemainingViewsVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretSecretVO as SecretSecretVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;
use SecretServerBundle\SecretInDDD\Domain\Util\Service\SecretInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\Repository\SecretRepositoryInterface;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretHashVO as SecretHashVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretCreatedAtVO as SecretCreatedAtVO;
use SecretServerBundle\SecretInDDD\Domain\Assembler\SecretAssembler;
use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;
use SecretServerBundle\SecretInDDD\Domain\DTO\SecretDTO;
use SecretServerBundle\SecretInDDD\Domain\DTO\SecretFormattedAndFilledDTO;
use DateTimeImmutable;

/**
 * SecretService
 */
class SecretService implements SecretInterface
{
    /**
     * @var SecretRepository
     */
    private $_secretRepository;

    /**
     * SecretService constructor.
     *
     * @param SecretRepository $secretRepository
     */
    public function __construct(SecretRepositoryInterface $secretRepository)
    {
        $this->_secretRepository = $secretRepository;
    }

    /**
     * Get list secret items
     *
     * @return array
     */
    public function getAll() : array
    {
        $allSecret          = $this->_secretRepository->getAllSecretItem();
        $allFormattedSecret = [];

        if (empty($allSecret) === TRUE) {
            return $allFormattedSecret;
        }

        foreach ($allSecret as $secret) {
            $secretDTO                   = new SecretDTO();
            $secretFormattedAndFilledDTO = new SecretFormattedAndFilledDTO();

            $secretDTO->setId((int) $secret['id']);
            $secretDTO->setHash($secret['hash']);
            $secretDTO->setSecret($secret['secret']);
            $secretDTO->setRemainingViews((int) $secret['remainingViews']);
            $secretDTO->setExpiresAt((int) $secret['expiresAt']);
            $secretDTO->setCreatedAt(new DateTimeImmutable($secret['createdAt']->format('Y-m-d H:i:s')));

            $secretFormattedAndFilledDTO->setHash($secretDTO->getHash());
            $secretFormattedAndFilledDTO->setSecret($secretDTO->getSecret());
            $secretFormattedAndFilledDTO->setCreatedAt($secretDTO->getCreatedAt()->format('Y-m-d\TH-i-s.\0\0\0\Z'));
            $secretFormattedAndFilledDTO->setExpiresAt($this->getExpiresAtDateTime($secretDTO)->format('Y-m-d\TH-i-s.\0\0\0\Z'));
            $secretFormattedAndFilledDTO->setRemainingViews($secretDTO->getRemainingViews());

            $allFormattedSecret[] = $secretFormattedAndFilledDTO;
        }

        return $allFormattedSecret;
    }

    /**
     * @param SecretVO $externalSecretVO
     *
     * @return SecretDTOInterface
     * @throws \SecretServerBundle\SecretInDDD\Domain\Exception\InvalidArgumentException
     */
    public function createNew(SecretVO $externalSecretVO) : SecretDTOInterface
    {
        $now             = new DateTimeImmutable();
        $secretAssembler = new SecretAssembler();
        $createSecretVO  = new SecretVO(
            NULL,
            new SecretHashVO(uniqid('se-' . md5(rand(1, 99999999)) . '-' . $now->format('YmdHis'))),
            $externalSecretVO->getSecret(),
            $externalSecretVO->getRemainingViews(),
            $externalSecretVO->getSecretExpiresAt(),
            new SecretCreatedAtVO(new DateTimeImmutable($externalSecretVO->getSecretCreatedAt()->getValue()))
        );

        return $secretAssembler->transform($this->_secretRepository->addNew($createSecretVO));
    }

    /**
     * Get secret item by hash
     *
     * @param   string   $hash
     *
     * @return SecretDTOInterface
     */
    public function getByHash(string $hash) : SecretDTOInterface
    {
        $secretAssembler = new SecretAssembler();

        return $secretAssembler->transform($this->_secretRepository->getSecretByHash($hash));
    }

    /**
     * Get Expires at data time.
     * It's use created at param.
     *
     * @param SecretDTOInterface $secretItem
     *
     * @return DateTimeImmutable
     *
     * @throws \Exception
     */
    public function getExpiresAtDateTime(SecretDTOInterface $secretDTO) : DateTimeImmutable
    {
        if (empty($secretDTO->getExpiresAt())) {
            return $secretDTO->getCreatedAt();
        }

        $expiresAt = new DateTimeImmutable($secretDTO->getCreatedAt()->format('Y-m-d H:i:s'));

        return $expiresAt->modify("+{$secretDTO->getExpiresAt()} minutes");
    }

    /**
     * Reduce remaining views count
     *
     * @param SecretDTOInterface $secretEntity
     *
     * @return SecretDTOInterface
     *
     * @throws \Exception
     */
    public function reduceRemainingViewsCount(SecretDTOInterface $secretDTO) : SecretDTOInterface
    {
        $actualRemainingViewsCount = (int) $secretDTO->getRemainingViews();
        $secretAssembler           = new SecretAssembler();

        if ($actualRemainingViewsCount == 1) {
            $secretDTO->setRemainingViews(-1);
        } elseif ($actualRemainingViewsCount > 1) {
            $secretDTO->setRemainingViews(--$actualRemainingViewsCount);
        }

        $changeSecretVO = new SecretVO(
            new SecretIdVO($secretDTO->getId()),
            new SecretHashVO($secretDTO->getHash()),
            new SecretSecretVO($secretDTO->getSecret()),
            new SecretRemainingViewsVO($secretDTO->getRemainingViews()),
            new SecretExpiresAtVO($secretDTO->getExpiresAt()),
            new SecretCreatedAtVO(new DateTimeImmutable($secretDTO->getCreatedAt()->format('Y-m-d H:i:s')))
        );

        return $secretAssembler->transform($this->_secretRepository->save($changeSecretVO));
    }

    public function isExpired(SecretDTOInterface $secretDTO) : void
    {
        if ($secretDTO->getCreatedAt() !== $this->getExpiresAtDateTime($secretDTO) && $this->getExpiresAtDateTime($secretDTO) < new DateTimeImmutable()) {
            throw new \Exception('Expired - ExpiresAt!');
        }
    }

    public function isViewable(SecretDTOInterface $secretDTO) : void
    {
        if ((int) $secretDTO->getRemainingViews() < 0) {
            throw new \Exception('Expired - RemainingViews!');
        }
    }
}
