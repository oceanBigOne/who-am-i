<?php

namespace App\Entity;

use App\Traits\DeletedTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UniqueIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CharacterRepository")
 * @ORM\Table(name="`character`")
 */
class Character
{
    use TimestampableTrait;
    use UniqueIdTrait;
    use DeletedTrait;

    const NAMES = [
        'Bruce Willis',
        'Calamity Jane',
        'Spiderman',
        'Marie Curie',
        'Mickey',
        'Lara Croft',
        "Sophie Marceau",
        "Marion Cotillard",
        "Florence Foresti",
        "Josiane Balasko",
        "Alexandra Lamy",
        "Mimie Mathy",
        "Louane",
        "Valérie Lemercier",
        "Line Renaud",
        "Vanessa Paradis",
        "Muriel Robin",
        "Karine Le Marchand",
        "Claire Chazal",
        "Mylène Farmer",
        "Zazie",
        "Nolwenn Leroy",
        "Catherine Deneuve",
        "Evelyne Dhéliat",
        "Laëtitia Milot",
        "Virginie Efira",
        "Sophie Davant",
        "Elise Lucet",
        "Jenifer",
        "Elodie Gossuin",
        "Amel Bent",
        "Jean-Jacques Goldman",
        "Omar Sy",
        "Dany Boon",
        "Soprano",
        "Jean Reno",
        "Francis Cabrel",
        "Teddy Riner",
        "Jean-Paul Belmondo",
        "Zinedine Zidane",
        "Jean-Luc Reichmann",
        "Jean Dujardin",
        "Thomas Pesquet",
        "Jean-Pierre Pernaut",
        "Kylian Mbappé",
        "Stéphane Plaza",
        "Stéphane Bern",
        "Renaud",
        "Guillaume Canet",
        "Fabrice Luchini",
        "Michel Sardou",
        "Christian Clavier",
        "Laurent Gerra",
        "Michel Cymes",
        "Gad Elmaleh",
        "Didier Deschamps","Astérix le gaulois",
        "Bill Gates",
        "Lancelot",
        "Superman",
        "Barack Obama",
        "Tintin",
        "Rayman",
        "Mario",
        "Sonic",
        "Einstein",
        "Alice au pays des merveilles",
        "Jack Sparrow",
        "Louis Vuitton",
        "Alphonse Daudet",
        "Casimir",
        "Napoléon",
        "Bugs Bunny",
        "Johnny Halliday",
        "Lucky Luke",
        "Charlie Chaplin",
        "La Joconde",
        "Dark Vador",
        "Le génie de la lampe",
        "Haroun Tazieff",
        "Schreck","La reine d’Angleterre",
        "Claude François",
        "Gaston Lagaffe",
        "Cindy Sanders",
        "Zavatta",
        "Louis XIV",
        "Angelina Jolie", "Jamel Debbouze", "Christophe Colomb", "Carla Bruni", "Lorie", "Nicolas Canteloup", "Napoléon", "Shakespeare", "Christophe Maé", "Picasso", "John Lennon", "Amélie Poulain", "Zinedine Zidane", "Diams", "Johnny Depp", "Carla Bruni", "Brad Pitt", "Barbie",
        "Batman", "Catwoman", "Superman", "Wonder Woman","Captain America", "Daredevil", "Hulk",
        "Lionel Messi","Agatha Christie","Hubert Reeves","Picasso","Steve Job", "Louis de Funès", "Mickey", "Chantal Goya", "Cousteau", "Galilée","Dorothée","Harry Potter", "Cyril Lignac", "Archimède", "L’Abbé Pierre","Louis XIV", "Céline Dion", "Cléopâtre", "Mr Bean", "Yannick Noah", "Isaac Newton", "Le Dalaï lama", "Homer Simpson", "Jean-Pierre Pernaut", "Jules Verne", "Mac Gyver", "Mimi Mathy", "Zinedine Zidane", "Michael Jackson", "Mime Marceau", "Marilyn Monroe"
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Character constructor.
     */
    public function __construct()
    {
        $this->uniqueId=$this->generateUniqueId();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
