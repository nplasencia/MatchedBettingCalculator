<?php declare(strict_types=1);

namespace Auret\MatchedBetting\DataFixtures;

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

    abstract protected function generateFakerData(): mixed;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NUMBER_OF_RECORDS; $i++) {
            $fakeData = $this->generateFakerData();
            $manager->persist($fakeData);
        }

        $manager->flush();
    }
}
