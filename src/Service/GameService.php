<?php

namespace App\Service;



use App\Entity\Character;
use App\Entity\Game;
use App\Entity\Player;
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
        $this->session->set("gameUniqueId",$game->getUniqueId());
        return $game;
    }

    /**
     * @param string $name
     * @param Game $game
     * @return Player
     * @throws \Exception
     */
    public function createPlayer(string $name, Game $game):Player{
        $player = new Player();
        $player->setCreatedAt(new \DateTime());
        $player->setUpdatedAt(new \DateTime());
        $player->setName($name);
        $player->setGame($game);
        //affect character
        $charactersRepo = $this->em->getRepository(Character::class);
        /**@var Character $character**/
        $character = $charactersRepo->findOneByRand($game);
        $player->setAffectedCharacter($character);
        $this->em->persist($player);
        $this->em->flush();
        $this->session->set("playerUniqueId",$player->getUniqueId());
        return $player;
    }

    public function joinGame(Game $game){
        $player=$this->getCurrentPlayer();
        if($player){
            $player->setGame($game);
        }
    }

    /**
     * @param string|null $uniqueId
     * @return Game|null
     */
    public function getGame(?string $uniqueId=null):?Game{
        $game=null;
        $oldPlayer=$this->getCurrentPlayer();
        if(!is_null($uniqueId)){
            $gameRepo= $this->em->getRepository(Game::class);
            /**@var Game $game **/
            $game = $gameRepo->findOneBy(["uniqueId"=>$uniqueId]);
            if(!is_null($game)) {
                $this->session->set("gameUniqueId", $game->getUniqueId());
                //if player isn't in this game, then QUIT other game and join this game
                if(!is_null($oldPlayer)){
                    $oldGame=$oldPlayer->getGame();
                    if(!is_null($oldGame)) {
                        if ($oldGame->getUniqueId() != $game->getUniqueId()) {
                            $oldPlayer->setGame($game);
                            //affect character
                            $charactersRepo = $this->em->getRepository(Character::class);
                            /**@var Character $character * */
                            $character = $charactersRepo->findOneByRand($game);
                            $oldPlayer->setAffectedCharacter($character);
                            $this->em->persist($oldPlayer);
                            $this->em->flush();
                        }
                    }
                }

            }
        }
        return $game;
    }

    /**
     * @param string|null $uniqueId
     * @return Player|null
     */
    public function getPlayer(?string $uniqueId=null):?Player{
        $player=null;
        if(!is_null($uniqueId)){
            $playerRepo= $this->em->getRepository(Player::class);
            /**@var Player $player **/
            $player = $playerRepo->findOneBy(["uniqueId"=>$uniqueId]);
            if(!is_null($player)) {
                $this->session->set("playerUniqueId", $player->getUniqueId());
            }
        }

        return $player;
    }


    /**
     *
     */
    public function exitGame(){
        $player=$this->getCurrentPlayer();
        $game=$this->getCurrentGame();
        if(!is_null($player)) {
            $this->em->remove($player);
        }
        if(!is_null($game)){
            if(count($game->getPlayers())===0){
                $this->em->remove($game);
            }
        }
        $this->em->flush();
        $this->session->set("gameUniqueId",null);
        $this->session->set("playerUniqueId",null);
    }

    /**
     * @return Game|null
     */
    public function getCurrentGame():?Game{
        return $this->getGame($this->session->get("gameUniqueId"));
    }

    /**
     * @return Player|null
     */
    public function getCurrentPlayer():?Player{
        return $this->getPlayer($this->session->get("playerUniqueId"));
    }


}