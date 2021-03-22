<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController {
    public function login(): Response
    {
        return $this->render('Login/login.twig');
    }
}