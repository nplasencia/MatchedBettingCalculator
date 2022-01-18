<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\BetProfiler\Entity\Exchange as CoreExchange;
use Auret\BetProfiler\Entity\LayBet as CoreLayBet;
use Auret\BetProfiler\Gateway\LayBetGatewayInterface;
use Auret\MatchedBetting\Entity\LayBet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LayBetRepository extends ServiceEntityRepository implements LayBetGatewayInterface
{
    private ExchangeRepository $exchangeRepository;

    public function __construct(ManagerRegistry $registry, ExchangeRepository $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
        parent::__construct($registry, LayBet::class);
    }

    public function add(CoreLayBet $layBet): CoreLayBet
    {
        $betLayBet = $this->convertFromCoreEntity($layBet);
        $this->getEntityManager()->persist($betLayBet);
        $this->getEntityManager()->flush();

        return $this->convertToCoreEntity($betLayBet);
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    private function convertFromCoreEntity(CoreLayBet $layBet): LayBet
    {
        $exchange = $this->exchangeRepository->find($layBet->getExchange()->getId());
        return new LayBet($layBet->getId(), $exchange, $layBet->getStake(), $layBet->getOdds(), $layBet->getLiability(), $layBet->getReturn(), $layBet->getProfit(), $layBet->getResult()->value);
    }

    private function convertToCoreEntity(LayBet $betLayBet): CoreLayBet
    {
        $exchange = new CoreExchange($betLayBet->getExchange()->getId(), $betLayBet->getExchange()->getName(), $betLayBet->getExchange()->getUrl());
        return new CoreLayBet($betLayBet->getId(), $exchange, $betLayBet->getStake(), $betLayBet->getOdds(), $betLayBet->getLiability(),$betLayBet->getReturn(), $betLayBet->getProfit(), BetResultEnum::from($betLayBet->getResult()));
    }
}