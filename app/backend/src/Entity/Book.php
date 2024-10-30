<?php

namespace App\Entity;

use App\Enum\AgeRating;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private Genre $genre;

    #[ORM\Column]
    private float $cost;

    #[ORM\Column(enumType: AgeRating::class)]
    private AgeRating $ageRating;

    public function __construct(
        string $name,
        float $cost,
        Genre $genre,
        AgeRating $ageRating
    )
    {
        $this->name = $name;
        $this->genre = $genre;
        $this->cost = $cost;
        $this->ageRating = $ageRating;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function getAgeRating(): AgeRating
    {
        return $this->ageRating;
    }
}
