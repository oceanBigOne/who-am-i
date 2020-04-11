<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SitePageController extends AbstractController
{
    /**
     * Homepage
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home/index.html.twig', [
        ]);
    }

    /**
     * CreateGame Page
     * @Route("/create-game", name="create_game")
     */
    public function createGame()
    {
        return $this->render('create-game/index.html.twig', [
        ]);
    }

    /**
     * JoinGame Page
     * @Route("/join-game", name="join_game")
     */
    public function joinGame()
    {
        return $this->render('join-game/index.html.twig', [
        ]);
    }

    /**
     * JoinGame Page
     * @Route("/rules", name="rules")
     */
    public function rules()
    {
        return $this->render('rules/index.html.twig', [
        ]);
    }

}
