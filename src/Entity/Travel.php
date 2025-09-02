<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
class Travel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $eco = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $travelPlace = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $availablePlace = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $depDateTime = null;

    #[ORM\Column(length: 255)]
    private ?string $depAddress = null;

    #[ORM\Column]
    private ?float $depGeoX = null;

    #[ORM\Column]
    private ?float $depGeoY = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $arrDateTime = null;

    #[ORM\Column(length: 255)]
    private ?string $arrAddress = null;

    #[ORM\Column]
    private ?float $arrGeoX = null;

    #[ORM\Column]
    private ?float $arrGeoY = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'travel')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    /**
     * @var Collection<int, TravelUser>
     */
    #[ORM\OneToMany(targetEntity: TravelUser::class, mappedBy: 'travel', orphanRemoval: true)]
    private Collection $travelUsers;

    public function __construct()
    {
        $this->travelUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEco(): ?bool
    {
        return $this->eco;
    }

    public function setEco(bool $eco): static
    {
        $this->eco = $eco;

        return $this;
    }

    public function getTravelPlace(): ?int
    {
        return $this->travelPlace;
    }

    public function setTravelPlace(int $travelPlace): static
    {
        $this->travelPlace = $travelPlace;

        return $this;
    }

    public function getAvailablePlace(): ?int
    {
        return $this->availablePlace;
    }

    public function setAvailablePlace(int $availablePlace): static
    {
        $this->availablePlace = $availablePlace;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDepDateTime(): ?\DateTimeImmutable
    {
        return $this->depDateTime;
    }

    public function setDepDateTime(\DateTimeImmutable $depDateTime): static
    {
        $this->depDateTime = $depDateTime;

        return $this;
    }

    public function getDepAddress(): ?string
    {
        return $this->depAddress;
    }

    public function setDepAddress(string $depAddress): static
    {
        $this->depAddress = $depAddress;

        return $this;
    }

    public function getDepGeoX(): ?float
    {
        return $this->depGeoX;
    }

    public function setDepGeoX(float $depGeoX): static
    {
        $this->depGeoX = $depGeoX;

        return $this;
    }

    public function getDepGeoY(): ?float
    {
        return $this->depGeoY;
    }

    public function setDepGeoY(float $depGeoY): static
    {
        $this->depGeoY = $depGeoY;

        return $this;
    }

    public function getArrDateTime(): ?\DateTimeImmutable
    {
        return $this->arrDateTime;
    }

    public function setArrDateTime(\DateTimeImmutable $arrDateTime): static
    {
        $this->arrDateTime = $arrDateTime;

        return $this;
    }

    public function getArrAddress(): ?string
    {
        return $this->arrAddress;
    }

    public function setArrAddress(string $arrAddress): static
    {
        $this->arrAddress = $arrAddress;

        return $this;
    }

    public function getArrGeoX(): ?float
    {
        return $this->arrGeoX;
    }

    public function setArrGeoX(float $arrGeoX): static
    {
        $this->arrGeoX = $arrGeoX;

        return $this;
    }

    public function getArrGeoY(): ?float
    {
        return $this->arrGeoY;
    }

    public function setArrGeoY(float $arrGeoY): static
    {
        $this->arrGeoY = $arrGeoY;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return Collection<int, TravelUser>
     */
    public function getTravelUsers(): Collection
    {
        return $this->travelUsers;
    }

    public function addTravelUser(TravelUser $travelUser): static
    {
        if (!$this->travelUsers->contains($travelUser)) {
            $this->travelUsers->add($travelUser);
            $travelUser->setTravel($this);
        }

        return $this;
    }

    public function removeTravelUser(TravelUser $travelUser): static
    {
        if ($this->travelUsers->removeElement($travelUser)) {
            // set the owning side to null (unless already changed)
            if ($travelUser->getTravel() === $this) {
                $travelUser->setTravel(null);
            }
        }

        return $this;
    }
}
