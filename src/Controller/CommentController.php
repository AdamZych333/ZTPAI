<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Meme;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    private $entityManager;

    /**
     * CommentController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/meme/{slug}/comments/{id}", name="delete_comment", methods={"DELETE"})
     * @ParamConverter("meme", options={"exclude": {"id"}})
     * @param Request $request
     * @param int $id
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function deleteComment(Request $request,
                                  int $id,
                                  CommentRepository $commentRepository
    ): Response
    {
        if($request->getMethod() == "GET"){
            return $this->redirectToRoute("home");
        }
        $comment = $commentRepository->find($id);
        $user = $this->getUser();
        if($comment->getAuthor() == $user or $this->isGranted("ROLE_ADMIN")){
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
            return new JsonResponse("", Response::HTTP_OK);
        }
        return new JsonResponse("", Response::HTTP_UNAUTHORIZED);

    }
}
