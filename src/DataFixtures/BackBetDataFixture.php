<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\MatchedBetting\Entity\BackBet;
use Auret\MatchedBetting\Entity\Bookmaker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class BackBetDataFixture extends MatchedBettingFixture implements DependentFixtureInterface
{
    protected function generateFakerData(): BackBet
    {
        /** @var Bookmaker $bookmaker */
        $bookmaker = $this->getRandomObjectReference(Bookmaker::class);
        $stake = $this->faker->randomFloat(2);
        $odds = $this->faker->randomFloat(2);
        $return = $this->faker->randomFloat(2);
        $profit = $this->faker->randomFloat(2);
        $resultEnum = $this->faker->randomElement(BetResultEnum::cases());

        return new BackBet(null, $bookmaker, $stake, $odds, $return, $profit, $resultEnum->value);
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [BookmakerDataFixture::class];
    }
}