<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {

        return $this->render('index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/perfil', name: 'profile')]
    public function profile(): Response
    {

        return $this->render('/user/profile.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
