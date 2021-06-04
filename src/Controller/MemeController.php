<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Dislike;
use App\Entity\Like;
use App\Entity\Meme;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Form\MemeFormType;
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
     * @Route("/meme/{slug}", name="meme", methods={"POST", "GET"})
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
        if($form->isSubmitted() && $form->isValid() && $this->getUser() != null){
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
     * @Route("/search", name="search")
     * @param Request $request
     * @param MemeRepository $memeRepository
     * @return Response
     */
    public function searchMemes(Request $request, MemeRepository $memeRepository): Response
    {
        $query = $request->query->get('q', "");

        return $this->render('Search/search.html.twig', [
            'memes' => $memeRepository->findByQuery($query)
        ]);
    }

    /**
     * @Route("/top10/{interval}", name="ranking", methods={"GET"})
     * @param String $interval
     * @param MemeRepository $memeRepository
     * @return Response
     */
     public function getRanking(String $interval,
                                MemeRepository $memeRepository
     ){
         switch ($interval){
             case 'Day':
                 $memes = $memeRepository->findByRatingAndDate(1);
                 break;
             case 'Week':
                 $memes = $memeRepository->findByRatingAndDate(7);
                 break;
             case 'Month':
                 $memes = $memeRepository->findByRatingAndDate(30);
                 break;
             default:
                 $memes = $memeRepository->findByRating();
         }

         $array = array();
         foreach ($memes as $meme){
             $array[] = array(
                 'id' => $meme->getId(),
                 'title' => $meme->getTitle(),
                 'created_at' => $meme->getCreatedAt(),
                 'image' => $meme->getImage(),
                 'slug' => $meme->getSlug(),
                 'user' => $meme->getCreatedBy()->getEmail(),
                 'likes' => $meme->getLikes()->count(),
                 'dislikes' => $meme->getDislikes()->count()
             );
         }
         return new JsonResponse($array, Response::HTTP_OK, ['Content-Type', 'application/json']);
     }

    /**
     * @Route("/add", name="add_meme")
     * @param Request $request
     * @return Response
     */
    public function addMeme(Request $request): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute("app_login");
        }
        $meme = new Meme();
        $form = $this->createForm(MemeFormType::class, $meme);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $image = $request->files->get('meme_form')['image'];
            $uploads_dir = $this->getParameter('uploads_dir');
            $name = md5(uniqid()) . "." . $image->guessExtension();
            $image->move(
                $uploads_dir,
                $name
            );
            $meme->setImage($name);
            /** @var User $user */
            $user = $this->getUser();
            $meme->setCreatedBy($user);
            $meme->setCreatedAtValue();

            $this->entityManager->persist($meme);
            $this->entityManager->flush();

            return $this->redirectToRoute("home");
        }

        return $this->render('Add/add.html.twig', [
            'meme_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/meme/{slug}", name="delete_meme", methods={"DELETE"})
     * @param Request $request
     * @param Meme $meme
     * @param MemeRepository $memeRepository
     * @return Response
     */
    public function deleteMeme(Request $request, Meme $meme, MemeRepository $memeRepository): Response
    {
        if($request->getMethod() == "GET"){
            return $this->redirectToRoute("home");
        }
        $user = $this->getUser();
        if($meme->getCreatedBy() == $user or $this->isGranted("ROLE_ADMIN")){
            $file_path = $this->getParameter('uploads_dir') ."/". $meme->getImage();
            unlink($file_path);
            $this->entityManager->remove($meme);
            $this->entityManager->flush();
            return new JsonResponse("", Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse("", Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Route("/meme/{slug}/like", name="like", methods={"POST"})
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
            $user->addLike($newLike);
            $this->entityManager->persist($newLike);
            $response->setStatusCode(Response::HTTP_CREATED);
        }else if($dislike == null) {
            $meme->removeLike($like);
            $user->removeLike($like);
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
     * @Route("/meme/{slug}/dislike", name="dislike", methods={"POST"})
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
            $user->addDislike($newDislike);
            $this->entityManager->persist($newDislike);
            $response->setStatusCode(Response::HTTP_CREATED);
        }else if($like == null){
            $meme->removeDislike($dislike);
            $user->removeDislike($dislike);
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
