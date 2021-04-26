<?php

namespace App\Controller;

use App\Repository\MemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $memes = $memeRepository->findAll();
        /*$arrayCollection = array();
        foreach ($memes as $item){
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'image' => $item->getImage(),
                'createdBy' => $item->getCreatedBy(),
                'createdAt' => $item->getCreatedAt(),
                'likes' => $item->getLikes(),
                'dislikes' => $item->getDislikes(),
                'comments' => count($item->getComments())
            );
        }

        return new JsonResponse($arrayCollection, Response::HTTP_OK, ['Content-Type', 'application/json']);
        */
        return $this->render('Home/home.html.twig', [
            'memes' => $memes
        ]);
    }
}
