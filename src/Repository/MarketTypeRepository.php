<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Entity\MarketType as CoreMarketType;
use Auret\BetProfiler\Gateway\MarketTypeGatewayInterface;
use Auret\MatchedBetting\Entity\MarketType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MarketTypeRepository extends ServiceEntityRepository implements MarketTypeGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketType::class);
    }

    public function add(CoreMarketType $marketType): void
    {
        $betMarketType = $this->convertFromCoreEntity($marketType);
        $this->getEntityManager()->persist($betMarketType);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, CoreMarketType $marketType): void
    {
        // TODO: Implement update() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function get(int $id): CoreMarketType
    {
        // TODO: Implement get() method.
    }

    private function convertFromCoreEntity(CoreMarketType $coreMarketType): MarketType
    {
        return new MarketType($coreMarketType->getId(), $coreMarketType->getName(), $coreMarketType->getCode());
    }
}
