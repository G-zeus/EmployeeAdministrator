<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityController extends AbstractController
{
    public function __construct(HttpClientInterface $HttpClient)
    {
        $this->httpClient = $HttpClient;
    }

    #[Route(path: '/login/empresa', name: 'app_login_company')]
    public function loginCompany(AuthenticationUtils $authenticationUtils)
    {
        $user = $this->getUser();
        if ($user)     {
            if (in_array('COMPANY',$user->getRoles()))
                return $this->redirectToRoute('admin');
            return $this->redirectToRoute('home');
        }


        return $this->login($authenticationUtils, 'empresa');

    }


    #[Route(path: '/login/empleado', name: 'app_login_user')]
    public function loginUser(AuthenticationUtils $authenticationUtils)
    {
        $user = $this->getUser();
        if ($user)     {
            if (in_array('USER',$user->getRoles()))
                return $this->redirectToRoute('profile');
            return $this->redirectToRoute('home');
        }

        return $this->login($authenticationUtils, 'empleado');

    }


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, String $role): Response
    {


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'role' =>$role]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
