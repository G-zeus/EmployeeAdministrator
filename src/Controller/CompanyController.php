<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends MainController
{
    #[Route('/company', name: 'app_company')]
    public function index(): Response
    {
            return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }
}
