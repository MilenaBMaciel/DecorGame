<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Dê um nome para a sala.')]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    // Quantos slots cada sala tem (para cada usuário)
    public const SLOT_COUNT = 3;

    /**
     * Slots de TODOS os usuários nesta sala. Para a decoração de um usuário,
     * use SlotManager::getSlotsFor().
     *
     * @var Collection<int, Slot>
     */
    #[ORM\OneToMany(targetEntity: Slot::class, mappedBy: 'room', orphanRemoval: true)]
    private Collection $slots;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): static
    {
        if (!$this->slots->contains($slot)) {
            $this->slots->add($slot);
            $slot->setRoom($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): static
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getRoom() === $this) {
                $slot->setRoom(null);
            }
        }

        return $this;
    }
}
