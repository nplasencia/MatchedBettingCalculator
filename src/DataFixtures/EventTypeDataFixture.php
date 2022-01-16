<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\EventType;

final class EventTypeDataFixture extends MatchedBettingFixture
{
    protected function generateFakerData(): EventType
    {
        $name = $this->faker->word;
        $code = strtoupper($name);

        return new EventType(null, $name, $code);
    }
}