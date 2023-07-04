<?php

namespace App\Controller;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends MainController
{

    #[Route('/company/{page}', name: 'company-index', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function home($page = 1): Response
    {
        $pagination = $this->entityManager->getRepository(User::class)->userList($page);
            return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
                'users' => $pagination
        ]);
    }


}
