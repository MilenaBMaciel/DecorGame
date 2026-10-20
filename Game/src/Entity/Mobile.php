<?php

namespace App\Entity;

use App\Repository\MobileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MobileRepository::class)]
class Mobile
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
    #[ORM\OneToMany(targetEntity: Slot::class, mappedBy: 'mobile')]
    private Collection $Slot;

    public function __construct()
    {
        $this->Slot = new ArrayCollection();
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
        return $this->Slot;
    }

    public function addSlot(Slot $slot): static
    {
        if (!$this->Slot->contains($slot)) {
            $this->Slot->add($slot);
            $slot->setMobile($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): static
    {
        if ($this->Slot->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getMobile() === $this) {
                $slot->setMobile(null);
            }
        }

        return $this;
    }
}
