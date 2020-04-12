<?php

namespace App\Controller;

use App\Service\GameService;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitePageController extends AbstractController
{

    /**
     * Homepage
     * @Route("/", name="home")
     * @param GameService $gameService
     * @param SessionService $session
     * @return Response
     * @throws \Exception
     */
    public function home(GameService $gameService, SessionService $session)
    {
        //if game already joint
        $game=null;
        if(!is_null($session->get("currentGameUniqueId"))){
            $game= $gameService->joinGame($session->get("currentGameUniqueId"))  ;
        }
        return $this->render('home/index.html.twig', ["game"=>$game]);
    }

    /**
     * Game page
     * @Route("/game/{uniqueId}", name="game", defaults={"uniqueId" = null})
     * @param GameService $gameService
     * @param SessionService $session
     * @param string|null $uniqueId
     * @return Response
     * @throws \Exception
     */
    public function game(GameService $gameService, SessionService $session, string $uniqueId=null)
    {
        //if no uniqueId, create a new game
        if(is_null($uniqueId)){
            $game = $gameService->createGame();
            return $this->redirectToRoute('game', ["uniqueId"=>$game->getUniqueId()]);
        }

        //if uniqueId, tying to join existing game
        $game= $gameService->joinGame($uniqueId);
        if(is_null($game)){
            $this->addFlash("error", "Cette partie n'existe pas !");
            return $this->redirectToRoute('home');
        }
        //store joint game
        $session->set("currentGameUniqueId",$game->getUniqueId());

        //if unknown player
        if(is_null($session->get("playerId")) || is_null($session->get("playerName")) ){
            return $this->redirectToRoute('player');
        }

        $this->addFlash("success", "Vous venez de rejoindre une partie");
        return $this->render('rules/index.html.twig', ["game"=>$game]);
    }

    /**
     * JoinGame Page
     * @Route("/player", name="player")
     */
    public function player()
    {
        return $this->render('rules/index.html.twig', [
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
