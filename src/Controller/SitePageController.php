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
     * @param SessionService $session
     * @return Response
     * @throws \Exception
     */
    public function home(GameService $gameService, SessionService $session)
    {
        //if game already joint
        $game=null;
        if(!is_null($session->get("gameUniqueId"))){
            $game= $gameService->joinGame($session->get("gameUniqueId"))  ;
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
            $session->set("playerUniqueId",null);
            $session->set("gameUniqueId",null);
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
        $session->set("gameUniqueId",$game->getUniqueId());

        //if unknown player
        if(is_null($session->get("playerUniqueId")) ){
            return $this->redirectToRoute('player');
        }

        return $this->render('game/index.html.twig', ["game"=>$game]);
    }

    /**
     * JoinGame Page
     * @Route("/player", name="player")
     * @param Request $request
     * @param SessionService $session
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function index(Request $request,SessionService $session)
    {
        $em = $this->getDoctrine()->getManager();
        $playerRepo= $em->getRepository(Player::class);
        $player=null;
        if(!is_null($session->get("playerUniqueId")) ){
            $player=$playerRepo->findOneBy(["uniqueId"=>$session->get("playerUniqueId")]);
        }
        if(is_null($player)){
            $player = new Player();
        }
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player->setCreatedAt(new \DateTime());
            $player->setUpdatedAt(new \DateTime());
            $player->setUpdatedAt(new \DateTime());
            $gameRepo=$em->getRepository(Game::class);
            /**
             * @var Game $game
             */
            $game=$gameRepo->findOneBy(["uniqueId"=>$session->get("gameUniqueId")]);
            $player->setGame($game);

            if(is_null($player->getAffectedCharacter())) {
                //give a random characters
                $charactersRepo = $em->getRepository(Character::class);
                /**
                 * @var Character $character
                 */
                $character = $charactersRepo->findOneByRand();
                $player->setAffectedCharacter($character);
            }
            $em->persist($player);
            $em->flush();
            $session->set("playerUniqueId",$player->getUniqueId());
            return $this->redirectToRoute('game', array('uniqueId' => $game->getUniqueId()));
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
     * Reset session (for dev only)
     * @Route("/reset", name="session_reset")
     * @param GameService $gameService
     * @param SessionService $session
     * @return Response
     * @throws \Exception
     */
    public function reset(GameService $gameService, SessionService $session)
    {
        $em = $this->getDoctrine()->getManager();
        $playerRepo= $em->getRepository(Player::class);
        $player=null;
        if(!is_null($session->get("playerUniqueId")) ){
            $player=$playerRepo->findOneBy(["uniqueId"=>$session->get("playerUniqueId")]);
            $em->remove($player);
            $em->flush();
        }

        $session->set("playerUniqueId",null);
        $session->set("gameUniqueId",null);
        return $this->redirectToRoute('home');
    }

}
