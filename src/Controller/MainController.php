<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController {
    /**
     * @Route("/", name="login")
     */
    public function login(): Response
    {
        $message = '';

        return $this->render('Login/login.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/", name="register")
     */
    public function register(): Response
    {
        $message = '';

        return $this->render('Register/register.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $title = 'Title-1';
        $image = 'uploads/meme1.jpg';
        $date = "28.03.2021";
        $id = 1;
        $likes = 60;
        $dislikes = 30;
        $memes = [new \Meme($title, $image, $date, $id, $likes, $dislikes),
            new \Meme($title, $image, $date, $id),
            new \Meme($title, $image, $date, $id),
        ];

        $name = 'Name';
        $surname = 'Surname';
        $joinedDate = "20.03.2021";
        $u_id = 1;
        $u_image = 'img/users/avatar.jpg';
        $lastOnline = "28.03.2021";
        $user = new \User($name, $surname, $joinedDate, $u_id, $u_image, $lastOnline);
        return $this->render('Home/home.html.twig', [
            'memes' => $memes,
            'user' => $user
        ]);
    }

    /**
     * @Route("/", name="top10")
     */
    public function top10(): Response
    {
        $title = 'Title-1';
        $image = 'uploads/meme1.jpg';
        $date = "28.03.2021";
        $id = 1;
        $likes = 60;
        $dislikes = 30;
        $memes = [new \Meme($title, $image, $date, $id, $likes, $dislikes),
            new \Meme($title, $image, $date, $id, $likes, $dislikes),
            new \Meme($title, $image, $date, $id, $likes, $dislikes),
        ];

        $name = 'Name';
        $surname = 'Surname';
        $joinedDate = "20.03.2021";
        $u_id = 1;
        $u_image = 'img/users/avatar.jpg';
        $lastOnline = "28.03.2021";
        $user = new \User($name, $surname, $joinedDate, $u_id, $u_image, $lastOnline);
        return $this->render('TOP10/top10.html.twig', [
            'memes' => $memes,
            'user' => $user
        ]);
    }

    /**
     * @Route("/", name="users")
     */
    public function users(): Response
    {
        $name = 'Name';
        $surname = 'Surname';
        $joinedDate = "20.03.2021";
        $u_id = 1;
        $u_image = 'img/users/avatar.jpg';
        $lastOnline = "28.03.2021";
        $users = [new \User($name, $surname, $joinedDate, $u_id, $u_image, $lastOnline),
            new \User($name, $surname, $joinedDate, $u_id, $u_image, $lastOnline),
            new \User($name, $surname, $joinedDate, $u_id, $u_image, $lastOnline)
        ];
        return $this->render('Users/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/", name="add")
     */
    public function add(): Response
    {
        return $this->render('Add/add.html.twig');
    }
}