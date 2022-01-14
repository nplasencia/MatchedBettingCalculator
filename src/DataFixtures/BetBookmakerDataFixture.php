<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\BetBookmaker;

final class BetBookmakerDataFixture extends MatchedBettingFixture
{
    protected function generateFakerData(): BetBookmaker
    {
        $name = $this->faker->name();
        $url = $this->faker->url();

        return new BetBookmaker(null, $name, $url);
    }
}