<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\MatchedBetting\Entity\Exchange;
use Auret\MatchedBetting\Entity\LayBet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class LayBetDataFixture extends MatchedBettingFixture implements DependentFixtureInterface
{
    protected function generateFakerData(int $i): LayBet
    {
        $betExchange = $this->getRandomObjectReference(Exchange::class);
        $stake = $this->faker->randomFloat(2);
        $odds = $this->faker->randomFloat(2);
        $liability = $this->faker->randomFloat(2);
        $return = $this->faker->randomFloat(2);
        $profit = $this->faker->randomFloat(2);
        $resultEnum = $this->faker->randomElement(BetResultEnum::cases());

        return new LayBet(null, $betExchange, $stake, $odds, $liability, $return, $profit, $resultEnum->value);
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [ExchangeDataFixture::class];
    }
}