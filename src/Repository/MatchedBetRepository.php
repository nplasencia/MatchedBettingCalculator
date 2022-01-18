<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Entity\MatchedBet as CoreMatchedBet;
use Auret\BetProfiler\Gateway\MatchedBetGatewayInterface;
use Auret\MatchedBetting\Entity\MatchedBet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MatchedBetRepository extends ServiceEntityRepository implements MatchedBetGatewayInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private BackBetRepository $backBetRepository,
        private LayBetRepository $layBetRepository,
        private EventRepository $eventRepository,
        private MarketTypeRepository $marketTypeRepository
    ) {
        parent::__construct($registry, MatchedBet::class);
    }

    public function add(CoreMatchedBet $matchedBet): void
    {
        $betMatchedBet = $this->convertFromCoreEntity($matchedBet);
        $this->getEntityManager()->persist($betMatchedBet);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    private function convertFromCoreEntity(CoreMatchedBet $coreMatchedBet): MatchedBet
    {
        $backBet = $this->backBetRepository->find($coreMatchedBet->getBackBet()->getId());
        $layBet = $this->layBetRepository->find($coreMatchedBet->getLayBet()->getId());
        $event = $this->eventRepository->find($coreMatchedBet->getEvent()->getId());
        $marketType = $this->marketTypeRepository->find($coreMatchedBet->getMarketType()->getId());

        return new MatchedBet($coreMatchedBet->getId(), $backBet, $layBet, $event, $marketType, $coreMatchedBet->getBet(), $coreMatchedBet->getType()->value, $coreMatchedBet->getMode()->value, $coreMatchedBet->getNotes());
    }
}
