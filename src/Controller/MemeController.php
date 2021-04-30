<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\MemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemeController extends AbstractController
{
    /**
     * @Route("/meme/{id}", name="meme", methods={"GET"})
     * @param Request $request
     * @param MemeRepository $memeRepository
     * @param CommentRepository $commentRepository
     * @param int $id
     * @return Response
     */
    public function getMemeById(Request $request,
                                MemeRepository $memeRepository,
                                CommentRepository $commentRepository,
                                int $id): Response
    {
        $meme = $memeRepository->find($id);
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($meme, $offset);

        return $this->render('Meme/meme.html.twig', [
                'meme' => $meme,
                'comments' => $paginator,
                'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE)
        ]);
    }
}
