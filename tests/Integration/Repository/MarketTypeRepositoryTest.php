<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Entity\MarketType;
use Auret\MatchedBetting\Repository\MarketTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class MarketTypeRepositoryTest extends KernelTestCase
{
    private MarketTypeRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var MarketTypeRepository $repository */
        $repository = $container->get(MarketTypeRepository::class);
        $this->repository = $repository;
    }

    public function testAdd_oneEventType_success(): void
    {
        $marketTypeName = 'Some market type Name';
        $marketTypeCode = 'some_market_type_code';

        $eventType = new MarketType(null, $marketTypeName, $marketTypeCode);
        $this->repository->add($eventType);

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertEquals($marketTypeName, $records[0]->getName());
        $this->assertEquals($marketTypeCode, $records[0]->getCode());
    }

    public function testAdd_someBookmakers_success(): void
    {
        $marketTypeName = 'Some market type Name';
        $marketTypeCode = 'some_market_type_code';

        $eventType = new MarketType(null, $marketTypeName, $marketTypeCode);
        $this->repository->add($eventType);
        $this->repository->add($eventType);
        $this->repository->add($eventType);

        $records = $this->repository->findAll();
        $this->assertCount(3, $records);
        foreach ($records as $record) {
            $this->assertEquals($marketTypeName, $record->getName());
            $this->assertEquals($marketTypeCode, $record->getCode());
        }
    }
}