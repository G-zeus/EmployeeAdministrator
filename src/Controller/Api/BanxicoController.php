<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BanxicoController extends AbstractController
{


    protected $httpClient;
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/api/uid', name: 'app_api_banxico')]
    public function index(): Response
    {

        $response = $this->httpClient->request("GET", "https://www.banxico.org.mx/SieAPIRest/service/v1/doc/consultaSeries",
            [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]

        );

        return new JsonResponse($response->toArray());
    }
}
