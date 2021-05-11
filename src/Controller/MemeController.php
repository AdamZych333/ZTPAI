<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Meme;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\MemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemeController extends AbstractController
{
    private $entityManager;

    /**
     * MemeController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/meme/{slug}", name="meme")
     * @param Request $request
     * @param Meme $meme
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function getMeme(Request $request,
                                Meme $meme,
                                CommentRepository $commentRepository
    ): Response{
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment->setMeme($meme);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('meme', ['slug' => $meme->getSlug()]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($meme, $offset);

        return $this->render('Meme/meme.html.twig', [
                'meme' => $meme,
                'comments' => $paginator,
                'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
                'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
                'comment_form' => $form->createView()
        ]);
    }
}
