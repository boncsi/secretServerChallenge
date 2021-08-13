<?php

namespace SecretServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SecretServerBundle\Util\SecretInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/secret/list", name="getSecretList", methods={"GET","HEAD", "OPTIONS"})
     */
    public function listAction(SecretInterface $secretService)
    {
        return $this->render('secret/list.html.twig', ['secretItems' => $secretService->getListItems()]);
    }

    /**
     * @Route("/secret/{hash}", name="getSecretByHash", methods={"GET","HEAD", "OPTIONS"})
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
     * @Route("/secret", name="addNewSecret", methods={"POST","HEAD", "OPTIONS"})
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
