<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\BetProfiler\Entity\LayBet as CoreLayBet;
use Auret\BetProfiler\Entity\Exchange as CoreExchange;
use Auret\MatchedBetting\Entity\LayBet;
use Auret\MatchedBetting\Repository\LayBetRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LayBetRepositoryTest extends KernelTestCase
{
    private LayBetRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var LayBetRepository $repository */
        $repository = $container->get(LayBetRepository::class);
        $this->repository = $repository;
    }

    public function testAdd_oneLayBet_success(): void
    {
        $exchangeId = 1;
        $layBetStake = 100.2;
        $layBetOdds = 4.3;
        $layBetLiability = 234.6;
        $layBetReturn = 125.19;
        $layBetProfit = -2.5;
        $layBetResult = BetResultEnum::WIN;
        $layBetResultValue = $layBetResult->value;

        $exchange = new CoreExchange($exchangeId, null, null);
        $layBet = new CoreLayBet(null, $exchange, $layBetStake, $layBetOdds, $layBetLiability, $layBetReturn, $layBetProfit, $layBetResult);
        $this->repository->add($layBet);

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);

        /** @var LayBet $layBet */
        $layBet = $records[0];
        $this->assertSame($exchangeId, $layBet->getExchange()->getId());
        $this->assertSame($layBetStake, $layBet->getStake());
        $this->assertSame($layBetOdds, $layBet->getOdds());
        $this->assertSame($layBetLiability, $layBet->getLiability());
        $this->assertSame($layBetReturn, $layBet->getReturn());
        $this->assertSame($layBetProfit, $layBet->getProfit());
        $this->assertSame($layBetResultValue, $layBet->getResult());
    }

    public function testAdd_someBookmakers_success(): void
    {
        $exchangeId = 1;
        $layBetStake = 100.2;
        $layBetOdds = 4.3;
        $layBetLiability = 234.6;
        $layBetReturn = 125.19;
        $layBetProfit = -2.5;
        $layBetResult = BetResultEnum::WIN;
        $layBetResultValue = $layBetResult->value;

        $exchange = new CoreExchange($exchangeId, null, null);
        $layBet = new CoreLayBet(null, $exchange, $layBetStake, $layBetOdds, $layBetLiability, $layBetReturn, $layBetProfit, $layBetResult);
        $this->repository->add($layBet);
        $this->repository->add($layBet);
        $this->repository->add($layBet);

        $records = $this->repository->findAll();
        $this->assertCount(3, $records);

        /** @var LayBet $layBet */
        foreach ($records as $layBet) {
            $this->assertSame($exchangeId, $layBet->getExchange()->getId());
            $this->assertSame($layBetStake, $layBet->getStake());
            $this->assertSame($layBetOdds, $layBet->getOdds());
            $this->assertSame($layBetLiability, $layBet->getLiability());
            $this->assertSame($layBetReturn, $layBet->getReturn());
            $this->assertSame($layBetProfit, $layBet->getProfit());
            $this->assertSame($layBetResultValue, $layBet->getResult());
        }
    }
}