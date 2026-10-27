<?php

namespace App\Entity;

use App\Repository\SlotRepository;
use Doctrine\ORM\Mapping as ORM;

// Cada usuário tem a própria decoração: um slot pertence a (sala, usuário, posição)
#[ORM\Entity(repositoryClass: SlotRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_SLOT_ROOM_USER_POSITION', columns: ['room_id', 'user_id', 'position'])]
class Slot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    private ?Mobile $mobile = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    // 1, 2 ou 3: da esquerda para a direita na tela
    #[ORM\Column]
    private ?int $position = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMobile(): ?Mobile
    {
        return $this->mobile;
    }

    public function setMobile(?Mobile $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
