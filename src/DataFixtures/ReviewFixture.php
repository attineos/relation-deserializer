<?php


namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Review;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ReviewFixture extends BaseFixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            BookFixtures::class,
        ];
    }

    protected function loadData(ObjectManager $om)
    {
        $this->createMany(Review::class, '', 100, function(Review $review, int $count) {

            $book = $this->getReference(self::getRefName(Book::class, '') . '_' . $count);

            $review->setBody($this->faker->text)
                ->setBook($book)
                ->setRating($this->faker->numberBetween(0,5))
                ->setAuthor($this->faker->name)
                ->setDate($this->faker->dateTime)
            ;
        });

        $om->flush();
    }

}