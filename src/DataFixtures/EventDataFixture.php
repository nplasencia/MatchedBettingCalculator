<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\Event;
use Auret\MatchedBetting\Entity\EventType;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class EventDataFixture extends MatchedBettingFixture implements DependentFixtureInterface
{
    protected function generateFakerData(): Event
    {
        $name = sprintf('%s vs %s', $this->faker->firstNameMale, $this->faker->firstNameMale);
        $dateTime = $this->faker->dateTimeInInterval('-1 years', '+2 years');
        /** @var EventType $eventType */
        $eventType = $this->getRandomObjectReference(EventType::class);

        return new Event(null, $name, $dateTime, $eventType);
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [EventTypeDataFixture::class];
    }
}