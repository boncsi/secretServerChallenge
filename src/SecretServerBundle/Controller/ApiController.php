<?php

namespace SecretServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SecretServerBundle\SecretInDDD\Application\Util\Service\SecretInterface;

class ApiController extends Controller
{
    /**
     * @Route("/api/secret/list", name="apiGetSecretList", methods={"GET","HEAD", "OPTIONS"})
     */
    public function listAction(SecretInterface $secretService)
    {
        $response    = new JsonResponse();
        $secretItems = $secretService->getListItems();

        if (empty($secretItems) === FALSE) {
            $responseItems = [];

            foreach ($secretItems as $secretItem) {
                $responseItem                   = [];
                $responseItem['hash']           = $secretItem->getHash();
                $responseItem['secret']         = $secretItem->getSecret();
                $responseItem['createdAt']      = $secretItem->getCreatedAt();
                $responseItem['expiresAt']      = $secretItem->getExpiresAt();
                $responseItem['remainingViews'] = $secretItem->getRemainingViews();

                $responseItems[] = $responseItem;
            }

            $response->setData($responseItems);

            return $response;
        }

        return $response->setStatusCode(404);
    }

    /**
     * @Route("/api/secret/{hash}", name="apiGetSecretByHash", methods={"GET","HEAD", "OPTIONS"})
     */
    public function getSecretAction($hash, SecretInterface $secretService)
    {
        $response         = new JsonResponse();
        $secretItemByHash = $secretService->getSecretByHash($hash);

        if (empty($secretItemByHash) === FALSE) {
            $response->setData($secretItemByHash);

            return $response;
        }

        return $response->setStatusCode(404);
    }

    /**
     * @Route("/api/secret", name="apiAddNewSecret", methods={"POST","HEAD", "OPTIONS"})
     */
    public function secretAction(Request $request, SecretInterface $secretService)
    {
        $response      = new JsonResponse();
        $newSecretItem = $secretService->createNew($request);

        if (empty($newSecretItem) === FALSE) {
            $response->setData($newSecretItem);

            return $response;
        }

        return $response->setStatusCode(405);
    }
}
