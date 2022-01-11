<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\BetExchange;
use Auret\BetProfiler\Entity\Exchange;
use Auret\BetProfiler\Gateway\ExchangeGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BetExchangeRepository extends ServiceEntityRepository implements ExchangeGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BetExchange::class);
    }

    public function add(Exchange $exchange): void
    {
        $betExchange = $this->convert($exchange);
        $this->getEntityManager()->persist($betExchange);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, Exchange $exchange): void
    {
        // TODO: Implement update() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function get(int $id): Exchange
    {
        // TODO: Implement get() method.
    }

    private function convert(Exchange $exchange): BetExchange
    {
        return new BetExchange($exchange->getId(), $exchange->getName(), $exchange->getUrl());
    }
}
