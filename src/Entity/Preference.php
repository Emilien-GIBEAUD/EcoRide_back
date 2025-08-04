<?php

namespace App\Entity;

use App\Repository\PreferenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreferenceRepository::class)]
class Preference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $smoker = null;

    #[ORM\Column]
    private ?bool $animal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otherPreference = null;

    #[ORM\OneToOne(inversedBy: 'preference', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isSmoker(): ?bool
    {
        return $this->smoker;
    }

    public function setSmoker(bool $smoker): static
    {
        $this->smoker = $smoker;

        return $this;
    }

    public function isAnimal(): ?bool
    {
        return $this->animal;
    }

    public function setAnimal(bool $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getOtherPreference(): ?string
    {
        return $this->otherPreference;
    }

    public function setOtherPreference(?string $otherPreference): static
    {
        $this->otherPreference = $otherPreference;

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
}
