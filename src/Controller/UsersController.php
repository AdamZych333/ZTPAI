<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController {
    public function users(): Response
    {
        return $this->render('Users/users.twig');
    }
}