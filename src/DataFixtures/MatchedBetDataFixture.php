<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\DataFixtures;

use Auret\BetProfiler\Common\MatchedBetModeEnum;
use Auret\BetProfiler\Common\MatchedBetTypeEnum;
use Auret\MatchedBetting\Entity\BackBet;
use Auret\MatchedBetting\Entity\Event;
use Auret\MatchedBetting\Entity\LayBet;
use Auret\MatchedBetting\Entity\MarketType;
use Auret\MatchedBetting\Entity\MatchedBet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class MatchedBetDataFixture extends MatchedBettingFixture implements DependentFixtureInterface
{
    protected function generateFakerData(int $i): MatchedBet
    {
        /** @var BackBet $backBet */
        $backBet = $this->getObjectReference(BackBet::class, $i);
        /** @var LayBet $layBet */
        $layBet = $this->getObjectReference(LayBet::class, $i);
        /** @var Event $event */
        $event = $this->getRandomObjectReference(Event::class);
        /** @var MarketType $marketType */
        $marketType = $this->getRandomObjectReference(MarketType::class);
        $bet = sprintf($this->faker->randomElement(['Win %s', 'Lose %s', 'Draw']), $this->faker->firstName);
        $betType = $this->faker->randomElement(MatchedBetTypeEnum::cases());
        $betMode = $this->faker->randomElement(MatchedBetModeEnum::cases());
        $notes = $this->faker->text;

        return new MatchedBet(null, $backBet, $layBet, $event, $marketType, $bet, $betType->value, $betMode->value, $notes);
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            BackBetDataFixture::class,
            LayBetDataFixture::class,
            EventDataFixture::class,
            MarketTypeDataFixture::class
        ];
    }
}