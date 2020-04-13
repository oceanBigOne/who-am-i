<?php

namespace App\Repository;

use App\Entity\Character;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    public function findOneByRand(Game $game)
    {
            $characters= $this->findAllByGame($game);
            $excludedList="0";
            foreach($characters as $character){
                $excludedList.=",".$character->getId();
            }
            return $this->getEntityManager()
                ->createQuery(
                    "SELECT c, RAND() as HIDDEN rand FROM App\Entity\Character c WHERE c NOT IN (".$excludedList.") ORDER BY rand")
                ->setMaxResults(1)->getOneOrNullResult();

    }

    public function findAllByGame(Game $game)
    {
        $rsm = new ResultSetMapping();
        return $this->getEntityManager()
            ->createNativeQuery("SELECT c.* 
FROM `character` as c 
LEFT JOIN `player` as p ON p.affected_character_id = c.id 
WHERE p.id = :game_id",$rsm)
            ->setParameter("game_id", $game->getId())
            ->getResult();
       /* return $this->createNativeQuery("SELECT c.*
FROM `character` as c LEFT JOIN `player` as p ON p.affected_character_id = c.id WHERE p.id = :game_id")->setParameter("game_id", $game->getId())
            ->getQuery()
            ->getResult()

        $stmt->execute(["game_id"=>$game->getId()]);
        return $stmt;*/

       /* return $this->createQueryBuilder('c')
            ->leftJoin('player', 'p', 'ON', 'p.affected_character_id = c.id')
            ->andWhere('p.id = :game_id')
            ->setParameter("game_id", $game->getId())
            ->getQuery()
            ->getResult();*/
        //SELECT * FROM `character` as c LEFT JOIN `player` as p ON p.affected_character_id = c.id WHERE p.id = 14
       /* return $this->getEntityManager()
            ->createQuery(
                "SELECT c FROM App\Entity\Character c JOIN App\Entity\Player p WITH p.affected_character_id = c.id WHERE p.id = :game_id")
            ->setParameter("game_id", $game->getId())
            ->getResult();*/
    }

    // /**
    //  * @return Character[] Returns an array of Character objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Character
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
