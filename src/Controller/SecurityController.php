<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="login")
     */
    public function login(): Response
    {
        $message = '';

        return $this->render('Login/login.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/", name="register")
     */
    public function register(): Response
    {
        $message = '';

        return $this->render('Register/register.html.twig', [
            'message' => $message
        ]);
    }
}
