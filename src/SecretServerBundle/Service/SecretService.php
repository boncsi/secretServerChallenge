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

    public function getListItems()
    {
        return $this->_secretRepository->getAllSecretItem();
    }

    public function createNew(Request $request)
    {
        $response = new JsonResponse();

        try {
            /* @var $secretItem Secret */
            $secretItem = $this->_secretRepository->addNew(
                [
                    'secret'         => filter_var($request->request->get('secret'), FILTER_SANITIZE_STRING),
                    'expiresAt'      => filter_var($request->request->get('expireAfter'), FILTER_VALIDATE_INT),
                    'remainingViews' => filter_var($request->request->get('expireAfterViews'), FILTER_VALIDATE_INT)
                ]
            );

            $response->setData($this->getFilledData($secretItem));
        } catch (\Exception $e) {
            $response->setStatusCode(405);
        }

        return $response;
    }

    public function getSecretByHash($hash)
    {
        $response = new JsonResponse();

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

            $response->setData($this->getFilledData($this->_secretRepository->reduceRemainingViewsCount($secretItem)));
        } catch (\Exception $e) {
            $response->setStatusCode(404);
        }

        return $response;
    }

    public function getExpiresAtDateTime(Secret $secretItem)
    {
        $expiresAt = new \DateTime($secretItem->getCreatedAt()->format('Y-m-d H:i:s'));

        return $expiresAt->modify("+{$secretItem->getExpiresAt()} minutes");
    }

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
