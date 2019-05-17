<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"author", "review", "category", "book"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Book", inversedBy="authors")
     * @Groups({"author"})
     */
    private $books;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"author", "review", "category", "book"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"author", "review"})
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Category", inversedBy="author", cascade={"persist", "remove"})
     * @Groups({"author", "review"})
     */
    private $category;
    

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
