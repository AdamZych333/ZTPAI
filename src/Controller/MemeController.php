<?php

namespace App\Controller;

use App\Repository\MemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemeController extends AbstractController
{
    /**
     * @Route("/meme/{id}", name="meme", methods={"GET"})
     * @param MemeRepository $memeRepository
     * @param int $id
     * @return Response
     */
    public function getMemeById(MemeRepository $memeRepository, int $id): Response
    {
        $meme = $memeRepository->find($id);
        return $this->render('Meme/meme.html.twig', [
                'meme' => $meme
        ]);
    }
}
