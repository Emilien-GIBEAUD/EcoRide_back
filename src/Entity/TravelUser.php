<?php

namespace App\Entity;

use App\Repository\TravelUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelUserRepository::class)]
class TravelUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $travelRole = null;

    #[ORM\ManyToOne(inversedBy: 'travelUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'travelUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Travel $travel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelRole(): ?string
    {
        return $this->travelRole;
    }

    public function setTravelRole(string $travelRole): static
    {
        $this->travelRole = $travelRole;

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

    public function getTravel(): ?Travel
    {
        return $this->travel;
    }

    public function setTravel(?Travel $travel): static
    {
        $this->travel = $travel;

        return $this;
    }
}
