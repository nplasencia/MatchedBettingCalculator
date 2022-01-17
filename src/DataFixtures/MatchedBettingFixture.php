<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\Bookmaker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class MatchedBettingFixture extends Fixture
{
    private const NUMBER_OF_RECORDS = 100;

    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    abstract protected function generateFakerData(int $i): mixed;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NUMBER_OF_RECORDS; $i++) {
            $fakeData = $this->generateFakerData($i);
            $manager->persist($fakeData);
            $this->addObjectReference($fakeData, $i);
        }

        $manager->flush();
    }

    protected function getRandomObjectReference(string $className): object
    {
        $randomIndex = rand(0, self::NUMBER_OF_RECORDS - 1);
        return $this->getObjectReference($className, $randomIndex);
    }

    protected function getObjectReference(string $className, int $i): object
    {
        return $this->getReference(sprintf('%s_%d', $className, $i));
    }

    private function addObjectReference(mixed $object, int $i): void
    {
        $referenceName = sprintf('%s_%d', get_class($object), $i);
        $this->addReference($referenceName, $object);
    }
}
