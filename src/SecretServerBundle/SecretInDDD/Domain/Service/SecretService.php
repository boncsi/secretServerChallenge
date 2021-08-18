<?php

namespace SecretServerBundle\SecretInDDD\Domain\Service;

use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;
use SecretServerBundle\Repository\SecretRepository;
use SecretServerBundle\Entity\Secret;
use SecretServerBundle\SecretInDDD\Domain\Util\Service\SecretInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\Repository\SecretRepositoryInterface;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretIdVO as SecretIdVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretHashVO as SecretHashVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretSecretVO as SecretSecretVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretRemainingViewsVO as SecretRemainingViewsVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretExpiresAtVO as SecretExpiresAtVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretCreatedAtVO as SecretCreatedAtVO;
use \DateTimeImmutable;
use SecretServerBundle\SecretInDDD\Domain\Assembler\SecretAssembler;
use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;
use SecretServerBundle\SecretInDDD\Domain\DTO\SecretDTO;
use SecretServerBundle\SecretInDDD\Domain\DTO\SecretFormattedAndFilledDTO;

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
     * @return array
     */
    public function getSecretByHash(string $hash) : array
    {
        try {
            /* @var $secretItem Secret */
            $secretItem = $this->_secretRepository->getSecretByHash($hash);

            if (empty($secretItem)) {
                throw new \Exception('Not be found!');
            }

            $nowDateTime = new \DateTime();

            if ($secretItem->getCreatedAt() !== $this->getExpiresAtDateTime($secretItem) && $this->getExpiresAtDateTime($secretItem) < $nowDateTime) {
                throw new \Exception('Expired - ExpiresAt!');
            }

            if ($secretItem->getRemainingViews() < 0) {
                throw new \Exception('Expired - RemainingViews!');
            }

            $secret = $this->getFilledData($this->_secretRepository->reduceRemainingViewsCount($secretItem));
        } catch (\Exception $e) {
            $secret = [];
        }

        return $secret;
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
}
