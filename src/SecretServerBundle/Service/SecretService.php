<?php

namespace SecretServerBundle\Service;

use Symfony\Component\HttpFoundation\Request;

use SecretServerBundle\Repository\SecretRepository;
use SecretServerBundle\Entity\Secret;
use SecretServerBundle\Util\Service\SecretInterface;
use SecretServerBundle\Util\Repository\SecretRepositoryInterface;

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
    public function getListItems() : array
    {
        return $this->_secretRepository->getAllSecretItem();
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function createNew(Request $request) : array
    {
        try {
            /* @var $secretItem Secret */
            $secretItem = $this->_secretRepository->addNew(
                [
                    'secret'         => filter_var($request->request->get('secret'), FILTER_SANITIZE_STRING),
                    'expiresAt'      => filter_var($request->request->get('expireAfter'), FILTER_VALIDATE_INT),
                    'remainingViews' => filter_var($request->request->get('expireAfterViews'), FILTER_VALIDATE_INT)
                ]
            );

            $newSecretItem = $this->getFilledData($secretItem);
        } catch (\Exception $e) {
            $newSecretItem = NULL;
        }

        return $newSecretItem;
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
     * @param Secret $secretItem
     *
     * @return \DateTime
     *
     * @throws \Exception
     */
    protected function getExpiresAtDateTime(Secret $secretItem)
    {
        $expiresAt = new \DateTime($secretItem->getCreatedAt()->format('Y-m-d H:i:s'));

        return $expiresAt->modify("+{$secretItem->getExpiresAt()} minutes");
    }

    /**
     * Get secret itme filled data.
     *
     * @param Secret $secretItem
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getFilledData(Secret $secretItem)
    {
        return [
            'hash'           => $secretItem->getHash(),
            'secretText'     => $secretItem->getSecret(),
            'createdAt'      => $secretItem->getCreatedAt()->format('Y-m-d\TH-i-s.\0\0\0\Z'),
            'expiresAt'      => $this->getExpiresAtDateTime($secretItem)->format('Y-m-d\TH-i-s.\0\0\0\Z'),
            'remainingViews' => $secretItem->getRemainingViews()
        ];
    }
}
