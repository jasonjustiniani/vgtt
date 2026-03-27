<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
#[ORM\Table('comments')]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: Types::STRING, nullable: false)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(name: 'email', type: Types::STRING, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(name: 'comment', type: Types::STRING, nullable: false)]
    #[Assert\NotBlank]
    private ?string $comment = null;

    #[ORM\Column(name: 'parent_comment_id', type: Types::INTEGER, nullable: true)]
    private ?int $parent_comment_id = null;

    #[ORM\Column(name: 'date_created', type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTime $date_created = null;

    #[ORM\Column(name: 'date_updated', type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTime $date_updated = null;

    public function __construct()
    {
        $this->date_created = new \DateTime();
        $this->date_updated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getParentCommentId(): ?int
    {
        return $this->parent_comment_id;
    }

    public function setParentCommentId(int $parent_comment_id): static
    {
        $this->parent_comment_id = $parent_comment_id;

        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->date_created;
    }

    public function setDateCreated(): static
    {
        $this->date_created = new \DateTime();

        return $this;
    }

    public function getDateUpdated(): ?\DateTime
    {
        return $this->date_updated;
    }

    public function setDateUpdated(): static
    {
        $this->date_updated = new \DateTime();

        return $this;
    }
}
