<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TOP10Controller extends AbstractController {
    public function top10(): Response
    {
        return $this->render('TOP10/top10.twig');
    }
}