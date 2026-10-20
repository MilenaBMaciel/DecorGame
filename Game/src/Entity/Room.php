<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Slot>
     */
    #[ORM\OneToMany(targetEntity: Slot::class, mappedBy: 'room', orphanRemoval: true)]
    private Collection $slot;

    public function __construct()
    {
        $this->slot = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Slot>
     */
    public function getSlot(): Collection
    {
        return $this->slot;
    }

    public function addSlot(Slot $slot): static
    {
        if (!$this->slot->contains($slot)) {
            $this->slot->add($slot);
            $slot->setRoom($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): static
    {
        if ($this->slot->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getRoom() === $this) {
                $slot->setRoom(null);
            }
        }

        return $this;
    }
}
