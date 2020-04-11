<?php

namespace App\Entity;

use App\Traits\TimestampableTrait;
use App\Traits\UniqueIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $secretKey;

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

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;

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
