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
    public function listAction()
    {
        $secretRepository = $this->getDoctrine()->getManager()->getRepository("SecretServerBundle:Secret");

        return $this->render('secret/list.html.twig', ['secretItems' => $secretRepository->getAllSecretItem()]);
    }

    /**
     * @Route("/secret/{hash}", name="getSecretByHash", methods={"GET","HEAD", "OPTIONS"})
     */
    public function getSecretAction($hash)
    {
        $response         = new JsonResponse();
        $secretRepository = $this->getDoctrine()->getManager()->getRepository("SecretServerBundle:Secret");

        try {
            /* @var $secretItem Secret */
            $secretItem = $secretRepository->getSecretByHash($hash);

            if (empty($secretItem)) {
                throw new \Exception('Not be found!');
            }

            $secretService = new SecretService($secretItem);
            $nowDateTime   = new \DateTime();

            if ($secretItem->getCreatedAt() !== $secretService->getExpiresAtDateTime() && $secretService->getExpiresAtDateTime() < $nowDateTime) {
                throw new \Exception('Expired - ExpiresAt!');
            }

            if ($secretItem->getRemainingViews() < 0) {
                throw new \Exception('Expired - RemainingViews!');
            }

            /* @var $secretItem Secret */
            $secretItem    = $secretRepository->reduceRemainingViewsCount($secretItem);
            $secretService = new SecretService($secretItem);

            $response->setData($secretService->getFilledData());
        } catch (\Exception $e) {
            $response->setStatusCode(404);
        }

        return $response;
    }

    /**
     * @Route("/secret", name="addNewSecret", methods={"POST","HEAD", "OPTIONS"})
     */
    public function secretAction(Request $request)
    {
        $response         = new JsonResponse();
        $secretRepository = $this->getDoctrine()->getManager()->getRepository("SecretServerBundle:Secret");

        try {
            /* @var $secretItem Secret */
            $secretItem = $secretRepository->addNew(
                [
                    'secret'         => filter_var($request->request->get('secret'), FILTER_SANITIZE_STRING),
                    'expiresAt'      => filter_var($request->request->get('expireAfter'), FILTER_VALIDATE_INT),
                    'remainingViews' => filter_var($request->request->get('expireAfterViews'), FILTER_VALIDATE_INT)
                ]
            );

            $secretService = new SecretService($secretItem);

            $response->setData($secretService->getFilledData());
        } catch (\Exception $e) {
            $response->setStatusCode(405);
        }

        return $response;
    }
}
