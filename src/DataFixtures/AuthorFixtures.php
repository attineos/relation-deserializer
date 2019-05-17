<?php


namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AuthorFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            BookFixtures::class,
            ReviewFixture::class,
        ];
    }


    protected function loadData(ObjectManager $om)
    {
        $this->createMany(Author::class, '', 100, function(Author $author, int $count) {

            /** @var Book $book */
            $book = $this->getRandomReference(self::getRefName(Book::class, ''));

            $author->setEmail($this->faker->email)
                ->setName($this->faker->name)
                ->addBook($book)
            ;
        });

        $om->flush();
    }

}