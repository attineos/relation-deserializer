<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"author", "book", "review"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"author", "book", "review"})
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      groups={"book_validation"}
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"author", "book", "review"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"author", "book", "review"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publicationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="book")
     * @Groups({"book", "author"})
     */
    private $reviews;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", mappedBy="books")
     * @Groups({"book", "review"})
     */
    private $authors;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsbn()
    {
        return $this->isbn;
    }


    public function setIsbn($isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setBook($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getBook() === $this) {
                $review->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
            $author->removeBook($this);
        }

        return $this;
    }

}
