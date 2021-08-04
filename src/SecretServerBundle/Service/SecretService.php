<?php

namespace SecretServerBundle\Service;

use SecretServerBundle\Repository\SecretRepository;
use SecretServerBundle\Entity\Secret;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * SecretService
 */
class SecretService
{
    /**
     * Entity Manager
     * @param EntityManagerInterface $_entityManager
     */
    private $_entityManager;

    /**
     * @var SecretRepository
     */
    private $_secretRepository;

    /**
     * SecretService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_entityManager    = $entityManager;
        $this->_secretRepository = $this->_entityManager->getRepository("SecretServerBundle:Secret");
    }


    /**
     * Get list secret items
     *
     * @return array
     */
    public function getListItems()
    {
        return $this->_secretRepository->getAllSecretItem();
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function createNew(Request $request)
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
    public function getSecretByHash($hash)
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
    public function getExpiresAtDateTime(Secret $secretItem)
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
    public function getFilledData(Secret $secretItem)
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
