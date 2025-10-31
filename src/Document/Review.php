<?php
namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ODM\Document(collection: 'reviews')]
class Review
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field(type: "int")]
    protected int $userId;

    #[Groups(['comment'])]
    #[ODM\Field(type: 'string')]
    public string $pseudo;

    #[Groups(['comment'])]
    #[ODM\Field(type: 'string')]
    public string $comment;

    #[Groups(['comment'])]
    #[ODM\Field(type: 'int')]
    private int $note;

    #[Groups(['comment'])]
    #[ODM\Field(type: 'date_immutable')]
    public \DateTimeImmutable $createdAt;


    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
    /**
     * Get the value of pseudo
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     */
    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of comment
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of note
     */
    public function getNote(): int
    {
        return $this->note;
    }

    /**
     * Set the value of note
     */
    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}