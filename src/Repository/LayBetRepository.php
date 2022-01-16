<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Entity\Exchange as CoreExchange;
use Auret\BetProfiler\Entity\LayBet as CoreLayBet;
use Auret\BetProfiler\Gateway\LayBetGatewayInterface;
use Auret\MatchedBetting\Entity\Exchange;
use Auret\MatchedBetting\Entity\LayBet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LayBetRepository extends ServiceEntityRepository implements LayBetGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LayBet::class);
    }

    public function add(CoreLayBet $layBet): CoreLayBet
    {

    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    private function convertFromCoreEntity(LayBet $layBet): LayBet
    {
        $betExchange = new Exchange();
        return new LayBet($layBet->getId(), $layBet);
    }

    private function convertToCoreEntity(LayBet $betLayBet): CoreLayBet
    {
        $exchange = new Exchange($betLayBet->getExchange()->getId(), $betLayBet->getExchange()->getName(), $betLayBet->getExchange()->getUrl());
        return new CoreLayBet($betLayBet->getId(), $exchange, $betLayBet->getStake(), $betLayBet->getOdds(), $betLayBet->getLiability(),$betLayBet->getReturn(), $betLayBet->getProfit(), $betLayBet->getResult());
    }
}