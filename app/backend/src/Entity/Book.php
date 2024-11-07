<?php

namespace App\Entity;

use App\Enum\AgeRating;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Language $language;

    #[ORM\Column]
    private int $rating;

    #[ORM\Column]
    private int $numberOfPages;

    #[ORM\Column(length: 255)]
    private ?string $img = null;

    /**
     * @var Collection<int, Author>
     */
    #[ORM\ManyToMany(targetEntity: Author::class)]
    private Collection $authors;

    #[ORM\Column]
    private int $year;

    public function __construct(
        string $name,
        float $cost,
        Genre $genre,
        AgeRating $ageRating,
        string $img,
        Author $author,
        Language $language,
        int $rating,
        int $numberOfPages,
        int $year
    )
    {
        $this->name = $name;
        $this->genre = $genre;
        $this->cost = $cost;
        $this->ageRating = $ageRating;
        $this->authors = new ArrayCollection();
        $this->img = $img;
        $this->authors->add($author);
        $this->language = $language;
        $this->rating = $rating;
        $this->numberOfPages = $numberOfPages;
        $this->year = $year;
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

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getNumberOfPages(): ?int
    {
        return $this->numberOfPages;
    }

    public function setNumberOfPages(int $numberOfPages): static
    {
        $this->numberOfPages = $numberOfPages;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }
}
