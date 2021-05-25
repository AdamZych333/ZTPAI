<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Dislikes;
use App\Entity\Likes;
use App\Entity\Meme;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\DislikesRepository;
use App\Repository\LikesRepository;
use App\Repository\MemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param LikesRepository $likesRepository
     * @param DislikesRepository $dislikesRepository
     * @return Response
     */
    public function like(Request $request,
                         Meme $meme,
                         LikesRepository $likesRepository,
                         DislikesRepository $dislikesRepository): Response
    {
        if($request->getMethod() == "GET"){
            return $this->redirectToRoute("home");
        }
        /** @var User $user */
        $user = $this->getUser();
        $like = $likesRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $dislike = $dislikesRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $response = new JsonResponse();

        if($like == null && $dislike == null){
            $meme->setLikes($meme->getLikes()+1);
            $newLike = new Likes();
            $newLike->setFromUser($user);
            $newLike->setMeme($meme);
            $this->entityManager->persist($newLike);
            $response->setStatusCode(Response::HTTP_CREATED);
        }else if($dislike == null) {
            $meme->setLikes($meme->getLikes() - 1);
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
     * @param DislikesRepository $dislikesRepository
     * @param LikesRepository $likesRepository
     * @return Response
     */
    public function dislike(Request $request,
                            Meme $meme,
                            DislikesRepository $dislikesRepository,
                            LikesRepository $likesRepository): Response
    {
        if($request->getMethod() == "GET"){
            return $this->redirectToRoute("home");
        }
        /** @var User $user */
        $user = $this->getUser();
        $dislike = $dislikesRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $like = $likesRepository->findOneBy(['meme' => $meme, 'from_user' => $user]);
        $response = new JsonResponse();

        if($dislike == null && $like == null){
            $meme->setDislikes($meme->getDislikes()+1);
            $newDislike = new Dislikes();
            $newDislike->setFromUser($user);
            $newDislike->setMeme($meme);
            $this->entityManager->persist($newDislike);
            $response->setStatusCode(Response::HTTP_CREATED);
        }else if($like == null){
            $meme->setDislikes($meme->getDislikes()-1);
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
