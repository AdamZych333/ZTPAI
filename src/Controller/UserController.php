<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function users(Request $request,
                          UserRepository $userRepository): Response
    {
        $query = $request->query->get('q', "");

        return $this->render('Users/users.html.twig', [
            'users' => $userRepository->findByQuery($query)
        ]);
    }


}
