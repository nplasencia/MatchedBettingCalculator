<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\MatchedBetting\Entity\Bookmaker;

final class BookmakerDataFixture extends MatchedBettingFixture
{
    protected function generateFakerData(): Bookmaker
    {
        $name = $this->faker->name();
        $url = $this->faker->url();

        return new Bookmaker(null, $name, $url);
    }
}