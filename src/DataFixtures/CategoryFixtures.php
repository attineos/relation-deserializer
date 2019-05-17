<?php


namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            BookFixtures::class,
            ReviewFixture::class,
            AuthorFixtures::class,
        ];
    }

    protected function loadData(ObjectManager $om)
    {

        $this->createMany(Category::class, '', 100, function(Category $category, int $count) {

            /** @var Author $author */
            $author = $this->getRandomReference(self::getRefName(Author::class, ''));

            $category->setName($this->faker->customCategory)
                ->setAuthor($author);
            ;
        });

        $om->flush();
    }

}