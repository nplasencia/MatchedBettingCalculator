<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\BetExchange;

final class BetExchangeDataFixture extends MatchedBettingFixture
{
    protected function generateFakerData(): BetExchange
    {
        $name = $this->faker->name();
        $url = $this->faker->url();

        return new BetExchange(null, $name, $url);
    }
}