<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Entity\EventType as CoreEventType;
use Auret\BetProfiler\Gateway\EventTypeGatewayInterface;
use Auret\MatchedBetting\Entity\EventType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventTypeRepository extends ServiceEntityRepository implements EventTypeGatewayInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventType::class);
    }

    public function add(CoreEventType $eventType): void
    {
        $betEventType = $this->convertFromCoreEntity($eventType);
        $this->getEntityManager()->persist($betEventType);
        $this->getEntityManager()->flush();
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, CoreEventType $eventType): void
    {
        // TODO: Implement update() method.
    }

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function get(int $id): CoreEventType
    {
        // TODO: Implement get() method.
    }

    private function convertFromCoreEntity(CoreEventType $coreTypeEvent): EventType
    {
        return new EventType($coreTypeEvent->getId(), $coreTypeEvent->getName(), $coreTypeEvent->getCode());
    }
}
