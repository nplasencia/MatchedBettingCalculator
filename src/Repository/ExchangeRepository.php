<?php declare(strict_types=1);

namespace Auret\MatchedBetting\Repository;

use Auret\MatchedBetting\Entity\Exchange;
use Auret\BetProfiler\Entity\Exchange as CoreExchange;
use Auret\BetProfiler\Gateway\ExchangeGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExchangeRepository extends ServiceEntityRepository implements ExchangeGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exchange::class);
    }

    public function add(CoreExchange $exchange): void
    {
        $betExchange = $this->convertFromCoreEntity($exchange);
        $this->getEntityManager()->persist($betExchange);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, CoreExchange $exchange): void
    {
        // TODO: Implement update() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function get(int $id): CoreExchange
    {
        // TODO: Implement get() method.
    }

    private function convertFromCoreEntity(CoreExchange $coreExchange): Exchange
    {
        return new Exchange($coreExchange->getId(), $coreExchange->getName(), $coreExchange->getUrl());
    }
}
