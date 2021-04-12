<?php

namespace App\Controller;

use App\Repository\MemeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param MemeRepository $memeRepository
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(MemeRepository $memeRepository): Response{
        return $this->render('Home/home.html.twig', [
            'memes' => $memeRepository->findAll()
        ]);
    }
}
