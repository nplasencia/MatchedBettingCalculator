<?php declare(strict_types=1);

namespace Auret\MatchedBetting\Repository;

use Auret\MatchedBetting\Entity\BetBookmaker;
use Auret\BetProfiler\Entity\Bookmaker;
use Auret\BetProfiler\Gateway\BookmakerGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BetBookmakerRepository extends ServiceEntityRepository implements BookmakerGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BetBookmaker::class);
    }

    public function add(Bookmaker $bookmaker): void
    {
        $betBookmaker = $this->convert($bookmaker);
        $this->getEntityManager()->persist($betBookmaker);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, Bookmaker $bookmaker): void
    {
        // TODO: Implement update() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function get(int $id): Bookmaker
    {
        // TODO: Implement get() method.
    }

    private function convert(Bookmaker $bookmaker): BetBookmaker
    {
        return new BetBookmaker($bookmaker->getId(), $bookmaker->getName(), $bookmaker->getUrl());
    }
}
