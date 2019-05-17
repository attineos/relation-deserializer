<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"category", "author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"category", "author"})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Author", mappedBy="category", cascade={"persist", "remove"})
     * @Groups({"category"})
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        // set (or unset) the owning side of the relation if necessary
        $newCategory = $author === null ? null : $this;
        if ($newCategory !== $author->getCategory()) {
            $author->setCategory($newCategory);
        }

        return $this;
    }
}
