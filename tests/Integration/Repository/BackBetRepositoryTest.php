<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\BetProfiler\Entity\BackBet as CoreBackBet;
use Auret\BetProfiler\Entity\Bookmaker as CoreBookmaker;
use Auret\MatchedBetting\Entity\BackBet;
use Auret\MatchedBetting\Repository\BackBetRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BackBetRepositoryTest extends KernelTestCase
{
    private BackBetRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var BackBetRepository $repository */
        $repository = $container->get(BackBetRepository::class);
        $this->repository = $repository;
    }

    public function testAdd_oneBackBet_success(): void
    {
        $bookmakerId = 1;
        $bookmakerName = 'Some bookmaker name';
        $bookmakerUrl = 'http://some.bookmaker.url';
        $backBetStake = 100.2;
        $backBetOdds = 4.3;
        $backBetReturn = 125.19;
        $backBetProfit = -2.5;
        $backBetResult = BetResultEnum::WIN;

        $bookmaker = new CoreBookmaker($bookmakerId, $bookmakerName, $bookmakerUrl);
        $backBet = new CoreBackBet(null, $bookmaker, $backBetStake, $backBetOdds, $backBetReturn, $backBetProfit, $backBetResult);
        $this->repository->add($backBet);

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);

        /** @var BackBet $backBet */
        $backBet = $records[0];
        $this->assertSame($bookmakerId, $backBet->getBookmaker()->getId());
        $this->assertSame($backBetStake, $backBet->getStake());
        $this->assertSame($backBetOdds, $backBet->getOdds());
        $this->assertSame($backBetReturn, $backBet->getReturn());
        $this->assertSame($backBetProfit, $backBet->getProfit());
        $this->assertSame($backBetResult, $backBet->getResult());
    }

    public function testAdd_someBookmakers_success(): void
    {
        $bookmakerId = 1;
        $bookmakerName = 'Some bookmaker name';
        $bookmakerUrl = 'http://some.bookmaker.url';
        $backBetStake = 100.2;
        $backBetOdds = 4.3;
        $backBetReturn = 125.19;
        $backBetProfit = -2.5;
        $backBetResult = BetResultEnum::WIN;

        $bookmaker = new CoreBookmaker($bookmakerId, $bookmakerName, $bookmakerUrl);
        $backBet = new CoreBackBet(null, $bookmaker, $backBetStake, $backBetOdds, $backBetReturn, $backBetProfit, $backBetResult);
        $this->repository->add($backBet);
        $this->repository->add($backBet);
        $this->repository->add($backBet);

        $records = $this->repository->findAll();
        $this->assertCount(3, $records);

        /** @var BackBet $backBet */
        foreach ($records as $backBet) {
            $this->assertSame($bookmakerId, $backBet->getBookmaker()->getId());
            $this->assertSame($backBetStake, $backBet->getStake());
            $this->assertSame($backBetOdds, $backBet->getOdds());
            $this->assertSame($backBetReturn, $backBet->getReturn());
            $this->assertSame($backBetProfit, $backBet->getProfit());
            $this->assertSame($backBetResult, $backBet->getResult());
        }
    }
}