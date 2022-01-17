<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\Exchange;

final class ExchangeDataFixture extends MatchedBettingFixture
{
    protected function generateFakerData(int $i): Exchange
    {
        $name = $this->faker->name();
        $url = $this->faker->url();

        return new Exchange(null, $name, $url);
    }
}