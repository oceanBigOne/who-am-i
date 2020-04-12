<?php

namespace App\Entity;

use App\Traits\TimestampableTrait;
use App\Traits\UniqueIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player extends AbstractType
{
    use TimestampableTrait;
    use UniqueIdTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir un nom")
     *
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Character")
     */
    private $affectedCharacter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="players")
     */
    private $game;

    /**
     * Player constructor.
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

    public function getAffectedCharacter(): ?Character
    {
        return $this->affectedCharacter;
    }

    public function setAffectedCharacter(?Character $affectedCharacter): self
    {
        $this->affectedCharacter = $affectedCharacter;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
