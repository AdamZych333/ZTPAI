<?php

namespace App\Controller;

use App\Repository\MemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param MemeRepository $memeRepository
     * @return Response
     */
    public function home(MemeRepository $memeRepository): Response{

        return $this->render('Home/home.html.twig', [
            'memes' => $memeRepository->findBy([], ['created_at' => 'ASC'])
        ]);
    }

    /**
     * @Route("/top10", name="top10")
     * @param MemeRepository $memeRepository
     * @return Response
     */
    public function top10(MemeRepository $memeRepository): Response
    {
        return $this->render('TOP10/top10.html.twig', [
            'memes' => $memeRepository->findBy([], ['likes' => 'DESC'])
        ]);
    }
}
