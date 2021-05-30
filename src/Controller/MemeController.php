<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Dislike;
use App\Entity\Like;
use App\Entity\Meme;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\DislikeRepository;
use App\Repository\LikeRepository;
use App\Repository\MemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/meme/{slug}/like", name="like")
     * @param Request $request
     * @param Meme $meme
     * @param LikeRepository $likeRepository
     * @param DislikeRepository $dislikeRepository
     * @return Response
     */
    public function like(Request $request,
                         Meme $meme,
                         LikeRepository $likeRepository,
                         DislikeRepository $dislikeRepository): Response
    {
        if($request->getMethod() == "GET"){
            return $this->redirectToRoute("home");
        }
        /** @var User $user */
        $user = $this->getUser();
        $like = $likeRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $dislike = $dislikeRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $response = new JsonResponse();

        if($like == null && $dislike == null){
            $newLike = new Like();
            $newLike->setFromUser($user);
            $newLike->setMeme($meme);
            $meme->addLike($newLike);
            $this->entityManager->persist($newLike);
            $response->setStatusCode(Response::HTTP_CREATED);
        }else if($dislike == null) {
            $meme->removeLike($like);
            $this->entityManager->remove($like);
            $response->setStatusCode(Response::HTTP_OK);
        }else{
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        $this->entityManager->persist($meme);
        $this->entityManager->flush();

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }

    /**
     * @Route("/meme/{slug}/dislike", name="dislike")
     * @param Request $request
     * @param Meme $meme
     * @param DislikeRepository $dislikeRepository
     * @param LikeRepository $likeRepository
     * @return Response
     */
    public function dislike(Request $request,
                            Meme $meme,
                            DislikeRepository $dislikeRepository,
                            LikeRepository $likeRepository): Response
    {
        if($request->getMethod() == "GET"){
            return $this->redirectToRoute("home");
        }
        /** @var User $user */
        $user = $this->getUser();
        $dislike = $dislikeRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $like = $likeRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $response = new JsonResponse();

        if($dislike == null && $like == null){
            $newDislike = new Dislike();
            $newDislike->setFromUser($user);
            $newDislike->setMeme($meme);
            $meme->addDislike($newDislike);
            $this->entityManager->persist($newDislike);
            $response->setStatusCode(Response::HTTP_CREATED);
        }else if($like == null){
            $meme->removeDislike($dislike);
            $this->entityManager->remove($dislike);
            $response->setStatusCode(Response::HTTP_OK);
        }
        else{
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        $this->entityManager->persist($meme);
        $this->entityManager->flush();

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
