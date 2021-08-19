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
use SecretServerBundle\SecretInDDD\Domain\Util\DTO\SecretDTOInterface;
use DateTimeImmutable;

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
    public function getListItems() : array
    {
        return $this->_domainSecretService->getAll();
    }

    /**
     * @param Request $requestr
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
                    new SecretCreatedAtVO(new DateTimeImmutable())
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
     * @return ?array
     */
    public function getSecretByHash(string $hash) : ?array
    {
        try {
            /* @var $secretDTO SecretDTOInterface */
            $secretDTO = $this->_domainSecretService->getByHash($hash);

            $this->_domainSecretService->isExpired($secretDTO);
            $this->_domainSecretService->isViewable($secretDTO);

            $secretDTO = $this->_domainSecretService->reduceRemainingViewsCount($secretDTO);

            $secretAssembler             = new SecretAssembler();
            $secretFormattedAndFilledDTO = new SecretFormattedAndFilledDTO();

            $secretFormattedAndFilledDTO->setHash($secretDTO->getHash());
            $secretFormattedAndFilledDTO->setSecret($secretDTO->getSecret());
            $secretFormattedAndFilledDTO->setCreatedAt($secretDTO->getCreatedAt()->format('Y-m-d\TH-i-s.\0\0\0\Z'));
            $secretFormattedAndFilledDTO->setExpiresAt($this->_domainSecretService->getExpiresAtDateTime($secretDTO)->format('Y-m-d\TH-i-s.\0\0\0\Z'));
            $secretFormattedAndFilledDTO->setRemainingViews($secretDTO->getRemainingViews());

            return $secretAssembler->transformDTOToArray($secretFormattedAndFilledDTO);
        } catch (\Exception $e) {
            return [];
        }
    }
}
