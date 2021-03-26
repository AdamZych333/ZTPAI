<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController {
    public function login(): Response
    {
        return $this->render('Login/login.twig');
    }

    public function home(): Response
    {
        return $this->render('Home/home.twig');
    }
    public function top10(): Response
    {
        return $this->render('TOP10/top10.twig');
    }

    public function users(): Response
    {
        return $this->render('Users/users.twig');
    }

    public function add(): Response
    {
        return $this->render('Add/add.twig');
    }
}