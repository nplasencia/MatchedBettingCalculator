<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Entity\Exchange;
use Auret\MatchedBetting\Repository\ExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ExchangeRepositoryTest extends KernelTestCase
{
    private ExchangeRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var ExchangeRepository $repository */
        $repository = $container->get(ExchangeRepository::class);
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