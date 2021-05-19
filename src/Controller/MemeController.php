<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Meme;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\MemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/home", name="home")
     * @param MemeRepository $memeRepository
     * @return Response
     */
    public function home(MemeRepository $memeRepository): Response{

        return $this->render('Home/home.html.twig', [
            'memes' => $memeRepository->findAll()
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(): Response
    {
        return $this->render('Add/add.html.twig');
    }

    /**
     * @Route("/top10", name="top10")
     */
    public function top10(): Response
    {
        return $this->render('TOP10/top10.html.twig', [
            'memes' => [],
            'user' => null
        ]);
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
            /** @var User $user */
            $user = $this->getUser();
            $comment->setAuthor($user);

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

    /**
     * @Route("/meme/{slug}/comments/{id}", name="delete_comment")
     * @ParamConverter("meme", options={"exclude": {"id"}})
     * @param Meme $meme
     * @param int $id
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function deleteComment(Meme $meme,
                                  int $id,
                                  CommentRepository $commentRepository
    ): Response
    {
        $comment = $commentRepository->find($id);
        if($comment->getAuthor() !== $this->getUser()){
            throw $this->createAccessDeniedException();
        }
        $comment = $commentRepository->find($id);
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
        return $this->redirectToRoute('meme', ['slug' => $meme->getSlug()]);
    }
}
