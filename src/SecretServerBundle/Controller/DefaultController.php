<?php

namespace SecretServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SecretServerBundle\SecretInDDD\Application\Util\Service\SecretInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/secret/list", name="getSecretList", methods={"GET","HEAD", "OPTIONS"})
     */
    public function listAction(SecretInterface $secretService)
    {
        return $this->render('secret/list.html.twig', ['secretItems' => $secretService->getListItems()]);
    }
}
