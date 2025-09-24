<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['car', 'user'])]
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[Groups(['user'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['user'])]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Groups(['user'])]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Groups(['user'])]
    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[Groups(['user'])]
    #[ORM\Column(length: 255)]
    private ?string $avatarFile = 'user.svg';

    #[Groups(['user'])]
    #[Vich\UploadableField(
        mapping: "avatars",
        fileNameProperty: "avatarFile",
    )]
    #[Assert\File(
        maxSize: "500k",
        maxSizeMessage: "L'image ne doit pas dépasser {{ limit }}.",
        extensions: ["jpg", "jpeg", "png", "webp"],
        extensionsMessage: "L'extension du fichier est invalide ({{ extension }}). Les extensions valide sont : {{ extensions }}.",
    )]
    private ?File $avatarFileTemp = null;

    #[Groups(['user'])]
    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[Groups(['user'])]
    #[ORM\Column]
    private ?int $credit = null;

    #[Groups(['user'])]
    #[ORM\Column]
    private array $usageRole = [];

    #[ORM\Column(length: 255)]
    private ?string $apiToken = null;

    #[Groups(['user'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['user'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(['user'])]
    #[ORM\Column]
    private ?bool $active = null;

    #[Groups(['user'])]
    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Preference $preference = null;

    /**
     * @var Collection<int, Car>
     */
    #[Groups(['user'])]
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $cars;

    /**
     * @var Collection<int, TravelUser>
     */
    #[ORM\OneToMany(targetEntity: TravelUser::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $travelUsers;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->travelUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        $this->apiToken = bin2hex(random_bytes(20));

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getAvatarFile(): ?string
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?string $avatarFile): static
    {
        $this->avatarFile = $avatarFile;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $avatarFileTemp
     */
    public function setAvatarFileTemp(?File $avatarFileTemp = null): void
    {
        $this->avatarFileTemp = $avatarFileTemp;

        if (null !== $avatarFileTemp) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getAvatarFileTemp(): ?File
    {
        return $this->avatarFileTemp;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(int $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getUsageRole(): array
    {
        return $this->usageRole;
    }

    public function setUsageRole(array $usageRole): static
    {
        $this->usageRole = $usageRole;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): static
    {
        $this->apiToken = $apiToken;

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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getPreference(): ?Preference
    {
        return $this->preference;
    }

    public function setPreference(?Preference $preference): static
    {
        // unset the owning side of the relation if necessary
        if ($preference === null && $this->preference !== null) {
            $this->preference->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($preference !== null && $preference->getUser() !== $this) {
            $preference->setUser($this);
        }

        $this->preference = $preference;

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setUser($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getUser() === $this) {
                $car->setUser(null);
            }
        }

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
            $travelUser->setUser($this);
        }

        return $this;
    }

    public function removeTravelUser(TravelUser $travelUser): static
    {
        if ($this->travelUsers->removeElement($travelUser)) {
            // set the owning side to null (unless already changed)
            if ($travelUser->getUser() === $this) {
                $travelUser->setUser(null);
            }
        }

        return $this;
    }
}
