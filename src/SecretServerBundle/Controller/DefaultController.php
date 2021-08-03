<?php

namespace SecretServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SecretServerBundle\Entity\Secret;
use SecretServerBundle\Service\SecretService;

class DefaultController extends Controller
{
    /**
     * @Route("/secret/list", name="getSecretList", methods={"GET","HEAD", "OPTIONS"})
     */
    public function listAction(SecretService $secretService)
    {
        return $this->render('secret/list.html.twig', ['secretItems' => $secretService->getListItems()]);
    }

    /**
     * @Route("/secret/{hash}", name="getSecretByHash", methods={"GET","HEAD", "OPTIONS"})
     */
    public function getSecretAction($hash, SecretService $secretService)
    {
        return $secretService->getSecretByHash($hash);
    }

    /**
     * @Route("/secret", name="addNewSecret", methods={"POST","HEAD", "OPTIONS"})
     */
    public function secretAction(Request $request, SecretService $secretService)
    {
        return $secretService->createNew($request);
    }
}
