<?php

namespace App\Controller;

use App\Repository\MemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param MemeRepository $memeRepository
     * @return Response
     */
    public function index(MemeRepository $memeRepository): Response{
        return $this->render('Home/home.html.twig', [
            'memes' => $memeRepository->findAll()
        ]);
    }
}
