<?php

namespace App\Service;



use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;


    private $session;

    public function __construct(EntityManagerInterface $em, SessionService $session)
    {
        $this->em=$em;
        $this->session=$session;
    }

    /**
     * @return Game
     * @throws \Exception
     */
    public function createGame():Game{
        $game = new Game();
        $game->setCreatedAt(new \DateTime());
        $game->setUpdatedAt(new \DateTime());
        $this->em->persist($game);
        $this->em->flush();
        return $game;
    }

    /**
     * @return Game
     * @throws \Exception
     */
    public function joinGame(string $uniqueId):?Game{
        $gameRepo = $this->em->getRepository(Game::class);
        /**
         * @var Game $game
         */
        $game = $gameRepo->findOneBy(["uniqueId"=>$uniqueId]);
        return $game;
    }


}