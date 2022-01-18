<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\BetProfiler\Entity\BackBet as CoreBackBet;
use Auret\BetProfiler\Entity\Bookmaker as CoreBookmaker;
use Auret\BetProfiler\Gateway\BackBetGatewayInterface;
use Auret\MatchedBetting\Entity\BackBet;
use Auret\MatchedBetting\Entity\Bookmaker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BackBetRepository extends ServiceEntityRepository implements BackBetGatewayInterface
{
    private BookmakerRepository $bookmakerRepository;

    public function __construct(ManagerRegistry $registry, BookmakerRepository $bookmakerRepository)
    {
        $this->bookmakerRepository = $bookmakerRepository;
        parent::__construct($registry, BackBet::class);
    }

    public function add(CoreBackBet $backBet): CoreBackBet
    {
        $betBackBet = $this->convertFromCoreEntity($backBet);
        $this->getEntityManager()->persist($betBackBet);
        $this->getEntityManager()->flush();

        return $this->convertToCoreEntity($betBackBet);
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    private function convertFromCoreEntity(CoreBackBet $backBet): BackBet
    {
        //$bookmaker = new Bookmaker($backBet->getBookmaker()->getId(), $backBet->getBookmaker()->getName(), $backBet->getBookmaker()->getUrl());
        $bookmaker = $this->bookmakerRepository->find($backBet->getBookmaker()->getId());
        return new BackBet($backBet->getId(), $bookmaker, $backBet->getStake(), $backBet->getOdds(), $backBet->getReturn(), $backBet->getProfit(), $backBet->getResult()->value);
    }

    private function convertToCoreEntity(BackBet $backBet): CoreBackBet
    {
        $bookmaker = new CoreBookmaker($backBet->getBookmaker()->getId(), $backBet->getBookmaker()->getName(), $backBet->getBookmaker()->getUrl());
        return new CoreBackBet($backBet->getId(), $bookmaker, $backBet->getStake(), $backBet->getOdds(), $backBet->getReturn(), $backBet->getProfit(), BetResultEnum::from($backBet->getResult()));
    }
}