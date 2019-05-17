<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\fr_FR\Company;
use App\DataFixtures\Faker\Provider\CustomCategoryProvider;


abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private $manager;

    /** @var Generator */
    protected $faker;

    private $referenceIndex = [];

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');
        $this->faker->addProvider(new Company($this->faker));
        $this->faker->addProvider(new CustomCategoryProvider($this->faker));

        $this->loadData($manager);
    }

    protected function createMany(string $className, $refSuffix, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);

            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $refName = self::getRefName($className, $refSuffix);
            $this->addReference($refName . '_' . $i, $entity);
        }
    }

    protected function getRandomReference(string $className, string $refSuffix = '')
    {
        $refName = self::getRefName($className, $refSuffix);

        if (!isset($this->referenceIndex[$refName])) {
            $this->referenceIndex[$refName] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $refName . '_') === 0) {
                    $this->referenceIndex[$refName][] = $key;
                }
            }
        }

        if (empty($this->referenceIndex[$refName])) {
            throw new \Exception("Cannot find any references for class \"$className\"");
        }

        $randomReferenceKey = $this->faker->randomElement($this->referenceIndex[$refName]);

        return $this->getReference($randomReferenceKey);
    }

    protected static function getRefName(string $className, string $refSuffix = ''): string
    {
        if ($refSuffix == '') {
            return $className;
        }
        return "{$className}_{$refSuffix}";
    }
}