<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Entity\Exchange;
use Auret\MatchedBetting\Repository\BetExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BetExchangeRepositoryTest extends KernelTestCase
{
    private BetExchangeRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var BetExchangeRepository $repository */
        $repository = $container->get(BetExchangeRepository::class);
        $this->repository = $repository;
    }

    public function testAdd_oneExchange_success(): void
    {
        $exchangeName = 'Some Exchange Name';
        $exchangeUrl = 'http://some.exchange.url';

        $exchange = new Exchange(null, $exchangeName, $exchangeUrl);
        $this->repository->add($exchange);

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertEquals($exchangeName, $records[0]->getName());
        $this->assertEquals($exchangeUrl, $records[0]->getUrl());
    }

    public function testAdd_someExchanges_success(): void
    {
        $exchangeName = 'Some Exchange Name';
        $exchangeUrl = 'http://some.exchange.url';

        $exchange = new Exchange(null, $exchangeName, $exchangeUrl);
        $this->repository->add($exchange);
        $this->repository->add($exchange);
        $this->repository->add($exchange);

        $records = $this->repository->findAll();
        $this->assertCount(3, $records);
        foreach ($records as $record) {
            $this->assertEquals($exchangeName, $record->getName());
            $this->assertEquals($exchangeUrl, $record->getUrl());
        }
    }
}