<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TOP10Controller extends AbstractController
{
    /**
     * @Route("/top10", name="top10")
     */
    public function index(): Response
    {
        return $this->render('TOP10/top10.html.twig', [
            'memes' => [],
            'user' => null
        ]);
    }
}
