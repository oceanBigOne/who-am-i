<?php

namespace App\Command;

use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HydrateCharactersCommand extends Command
{
    protected static $defaultName = 'hydrate:characters';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Hydrate characters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $charactersRepo = $this->em->getRepository(Character::class);

        $io = new SymfonyStyle($input, $output);
        $io->title('Hydrate characters...');

        $charactersName = Character::NAMES;

        $io->progressStart(count($charactersName));
        $oldCharacters=$charactersRepo->findAll();
        $oldCharactersArray=[];
        foreach($oldCharacters as $oldCharacter){
            /**
             * @var Character $oldCharacter;
             */
            $oldCharactersArray[]=$oldCharacter->getName();
            if (!in_array($oldCharacter->getName(),$charactersName)) {
                $oldCharacter->setIsDeleted(true);
                $oldCharacter->setUpdatedAt(new \DateTime());
            }
        }

        foreach ($charactersName as $name) {


            if (in_array($name,$oldCharactersArray)) {
                $char=$charactersRepo->findOneBy(["name"=>$name]);
                if($char->getIsDeleted()){
                    $char->setIsDeleted(false);
                    $char->setUpdatedAt(new \DateTime());
                }
            }else{
                $char = new Character();
                $char->setCreatedAt(new \DateTime());
                $char->setUpdatedAt(new \DateTime());
            }
            $char->setName($name);
            $this->em->persist($char);
            $io->progressAdvance();
        }

        $this->em->flush();
        $io->progressFinish();

        $io->success("Characters hydrated successfully !");
        return 1;
    }
}
