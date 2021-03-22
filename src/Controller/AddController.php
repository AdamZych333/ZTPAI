<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AddController extends AbstractController {
    public function add(): Response
    {
        return $this->render('Add/add.twig');
    }
}