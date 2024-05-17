<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[UniqueEntity(fields: ['title'], message: 'There is already a post with this title')]
class Post
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 100)]
  #[Assert\NotBlank(message: 'Please enter a title')]
  #[Assert\Length(max: 100, maxMessage: 'The title cannot be longer than {{ limit }} characters')]
  #[Assert\Length(min: 5, minMessage: 'The title must be at least {{ limit }} characters long')]
  private ?string $title = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Assert\NotBlank(message: 'Please enter a content')]
  #[Assert\Length(min: 10, minMessage: 'The content must be at least {{ limit }} characters long')]
  #[Assert\Length(max: 1000, maxMessage: 'The content cannot be longer than {{ limit }} characters')]
  private ?string $content = null;

  #[ORM\ManyToOne(inversedBy: 'posts')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $author = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): static
  {
    $this->title = $title;

    return $this;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): static
  {
    $this->content = $content;

    return $this;
  }

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): static
  {
    $this->author = $author;

    return $this;
  }
}
