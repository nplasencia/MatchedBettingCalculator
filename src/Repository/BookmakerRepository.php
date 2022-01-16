<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\MatchedBetting\Entity\Bookmaker;
use Auret\BetProfiler\Entity\Bookmaker as CoreBookmaker;
use Auret\BetProfiler\Gateway\BookmakerGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookmakerRepository extends ServiceEntityRepository implements BookmakerGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmaker::class);
    }

    public function add(CoreBookmaker $bookmaker): void
    {
        $betBookmaker = $this->convertFromCoreEntity($bookmaker);
        $this->getEntityManager()->persist($betBookmaker);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, CoreBookmaker $bookmaker): void
    {
        // TODO: Implement update() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function get(int $id): CoreBookmaker
    {
        // TODO: Implement get() method.
    }

    private function convertFromCoreEntity(CoreBookmaker $coreBookmaker): Bookmaker
    {
        return new Bookmaker($coreBookmaker->getId(), $coreBookmaker->getName(), $coreBookmaker->getUrl());
    }
}
