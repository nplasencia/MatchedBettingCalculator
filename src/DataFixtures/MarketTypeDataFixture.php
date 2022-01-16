<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\MarketType;

final class MarketTypeDataFixture extends MatchedBettingFixture
{
    protected function generateFakerData(): MarketType
    {
        $name = $this->faker->word;
        $code = strtoupper($name);

        return new MarketType(null, $name, $code);
    }
}