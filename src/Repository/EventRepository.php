<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Entity\Event as CoreEvent;
use Auret\BetProfiler\Entity\EventType as CoreEventType;
use Auret\BetProfiler\Gateway\EventGatewayInterface;
use Auret\MatchedBetting\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository implements EventGatewayInterface
{
    private EventTypeRepository $eventTypeRepository;

    public function __construct(ManagerRegistry $registry, EventTypeRepository $eventTypeRepository)
    {
        $this->eventTypeRepository = $eventTypeRepository;
        parent::__construct($registry, Event::class);
    }

    public function add(CoreEvent $event): CoreEvent
    {
        $betEvent = $this->convertFromCoreEntity($event);
        $this->getEntityManager()->persist($betEvent);
        $this->getEntityManager()->flush();

        return $this->convertToCoreEntity($betEvent);
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    private function convertFromCoreEntity(CoreEvent $event): Event
    {
        $betEventType = $this->eventTypeRepository->find($event->getType()->getId());
        return new Event($event->getId(), $event->getName(), $event->getDateTime(), $betEventType);
    }

    private function convertToCoreEntity(Event $betEvent): CoreEvent
    {
        $eventType = new CoreEventType($betEvent->getEventType()->getId(), $betEvent->getEventType()->getName(), $betEvent->getEventType()->getCode());
        return new CoreEvent($betEvent->getId(), $betEvent->getName(), $betEvent->getDateTime(), $eventType);
    }
}