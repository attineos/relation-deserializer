<?php


namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $om)
    {
        $this->createMany(Book::class, '', 100, function(Book $book, int $count) {

            $book->setIsbn($this->faker->isbn13)
                ->setDescription($this->faker->text)
                ->setPublicationDate($this->faker->dateTime)
                ->setTitle($this->faker->text)
            ;
        });

        $om->flush();
    }

}