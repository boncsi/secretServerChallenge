<?php

namespace SecretServerBundle\SecretInDDD\Application\Service;

use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;
use Symfony\Component\HttpFoundation\Request;
use SecretServerBundle\SecretInDDD\Application\Util\Service\SecretInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\Service\SecretInterface AS SecretDomainInterface;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretIdVO as SecretIdVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretHashVO as SecretHashVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretSecretVO as SecretSecretVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretRemainingViewsVO as SecretRemainingViewsVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretExpiresAtVO as SecretExpiresAtVO;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretCreatedAtVO as SecretCreatedAtVO;
use SecretServerBundle\SecretInDDD\Domain\Assembler\SecretAssembler;
use SecretServerBundle\SecretInDDD\Domain\DTO\SecretFormattedAndFilledDTO;

/**
 * SecretService
 */
class SecretService implements SecretInterface
{
    /**
     * @var SecretDomainInterface
     */
    private $_domainSecretService;

    /**
     * SecretService constructor.
     *
     * @param SecretDomainInterface $_domainSecretService
     */
    public function __construct(SecretDomainInterface $domainSecretService)
    {
        $this->_domainSecretService = $domainSecretService;
    }

    /**
     * Get list secret items
     *
     * @return array
     */
    /*public function getListItems() : array
    {
        return $this->_secretRepository->getAllSecretItem();
    }*/

    /**
     * @param Request $request
     * @return ?array
     */
    public function createNew(Request $request) : ?array
    {
        try {
            /* @var $secretItem SecretDomainInterface */
            $secretDTO = $this->_domainSecretService->createNew(
                new SecretVO(
                    new SecretIdVO(NULL),
                    new SecretHashVO(NULL),
                    new SecretSecretVO(filter_var($request->request->get('secret'), FILTER_SANITIZE_STRING)),
                    new SecretRemainingViewsVO(filter_var($request->request->get('expireAfterViews'), FILTER_VALIDATE_INT)),
                    new SecretExpiresAtVO(filter_var($request->request->get('expireAfter'), FILTER_VALIDATE_INT)),
                    new SecretCreatedAtVO(new \DateTimeImmutable())
                )
            );

            $secretAssembler             = new SecretAssembler();
            $secretFormattedAndFilledDTO = new SecretFormattedAndFilledDTO();

            $secretFormattedAndFilledDTO->setHash($secretDTO->getHash());
            $secretFormattedAndFilledDTO->setSecret($secretDTO->getSecret());
            $secretFormattedAndFilledDTO->setCreatedAt($secretDTO->getCreatedAt()->format('Y-m-d\TH-i-s.\0\0\0\Z'));
            $secretFormattedAndFilledDTO->setExpiresAt($this->_domainSecretService->getExpiresAtDateTime($secretDTO)->format('Y-m-d\TH-i-s.\0\0\0\Z'));
            $secretFormattedAndFilledDTO->setRemainingViews($secretDTO->getRemainingViews());

            return $secretAssembler->transformDTOToArray($secretFormattedAndFilledDTO);
        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * Get secret item by hash
     *
     * @param   string   $hash
     *
     * @return array
     */
    /*public function getSecretByHash(string $hash) : array
    {
        try {*/
            /* @var $secretItem Secret */
            /*$secretItem = $this->_secretRepository->getSecretByHash($hash);

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
    }*/

    /**
     * Get Expires at data time.
     * It's use created at param.
     *
     * @param Secret $secretItem
     *
     * @return \DateTime
     *
     * @throws \Exception
     */
    /*protected function getExpiresAtDateTime(Secret $secretItem) : \DateTime
    {
        if (empty($secretItem->getExpiresAt())) {
            return $secretItem->getCreatedAt();
        }

        $expiresAt = new \DateTime($secretItem->getCreatedAt()->format('Y-m-d H:i:s'));

        return $expiresAt->modify("+{$secretItem->getExpiresAt()} minutes");
    }*/

    /**
     * Get secret itme filled data.
     *
     * @param Secret $secretItem
     *
     * @return array
     *
     * @throws \Exception
     */
    /*protected function getFilledData(Secret $secretItem) : array
    {
        return [
            'hash'           => $secretItem->getHash(),
            'secretText'     => $secretItem->getSecret(),
            'createdAt'      => $secretItem->getCreatedAt()->format('Y-m-d\TH-i-s.\0\0\0\Z'),
            'expiresAt'      => $this->getExpiresAtDateTime($secretItem)->format('Y-m-d\TH-i-s.\0\0\0\Z'),
            'remainingViews' => $secretItem->getRemainingViews()
        ];
    }*/
}
