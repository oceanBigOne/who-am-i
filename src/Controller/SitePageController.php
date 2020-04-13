<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Game;
use App\Entity\InvestmentSolution\InvestmentSolution;
use App\Entity\Player;
use App\Form\Admin\InvestmentSolutionCreateType;
use App\Form\PlayerType;
use App\Service\AppCustomValuesService;
use App\Service\GameService;
use App\Service\MediaManagerService;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitePageController extends AbstractController
{

    /**
     * Homepage
     * @Route("/", name="home")
     * @param GameService $gameService
     * @return Response
     * @throws \Exception
     */
    public function home(GameService $gameService)
    {
        $game = $gameService->getCurrentGame();
        $characters = $gameService->getAllCharacters();

        return $this->render('home/index.html.twig', ["game" => $game,'characters'=>$characters]);
    }

    /**
     * Game page
     * @Route("/game/{uniqueId}", name="game", defaults={"uniqueId" = null})
     * @param GameService $gameService
     * @param string|null $uniqueId
     * @return Response
     * @throws \Exception
     */
    public function game(GameService $gameService, string $uniqueId = null)
    {
        //if no uniqueId, create a new game
        if (is_null($uniqueId)) {
            $game = $gameService->createGame();
            return $this->redirectToRoute('game', ["uniqueId" => $game->getUniqueId()]);
        }

        //if uniqueId, tying to join existing game
        $game = $gameService->getGame($uniqueId);
        if (is_null($game)) {
            $this->addFlash("error", "Cette partie n'existe pas !");
            return $this->redirectToRoute('home');
        }

        //if no player redirect to form player
        $player = $gameService->getCurrentPlayer();
        if (is_null($player)) {
            return $this->redirectToRoute('player');
        }

        return $this->render('game/index.html.twig', ["game" => $game]);
    }

    /**
     * @Route("/player", name="player" )
     * @param Request $request
     * @param GameService $gameService
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function player(Request $request,GameService $gameService)
    {
        $player=$gameService->getCurrentPlayer();
        $game=$gameService->getCurrentGame();
        if (is_null($player)) {
            $player = new Player();
        }
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playersInGame=$gameService->getPlayersInGame($game);
            $playersName=[];
            foreach($playersInGame as $playerInGame){
                $playersName[]=mb_strtolower($playerInGame->getName());
            }
            if(in_array(mb_strtolower($player->getName()),$playersName)){
                $this->addFlash('error', "Ce nom est déjà utilisé !");
            }else{
                $gameService->createPlayer($player->getName(),$game);
                return $this->redirectToRoute('game', array('uniqueId' => $game->getUniqueId()));
            }

        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', "Vérifiez votre saisie !");
        }

        return $this->render('player/index.html.twig', [
            'form' => $form->createView(),
            'player' => $player
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

    /**
     * @param GameService $gameService
     * @Route("/exit", name="exit")
     * @return RedirectResponse
     */
    public function exit(GameService $gameService)
    {
        $gameService->exitGame();
        return $this->redirectToRoute('home');
    }

}
