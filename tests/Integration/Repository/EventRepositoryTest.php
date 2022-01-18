<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Entity\Event as CoreEvent;
use Auret\BetProfiler\Entity\EventType as CoreEventType;
use Auret\MatchedBetting\Entity\Event;
use Auret\MatchedBetting\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class EventRepositoryTest extends KernelTestCase
{
    private EventRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var EventRepository $repository */
        $repository = $container->get(EventRepository::class);
        $this->repository = $repository;
    }

    public function testAdd_oneBackBet_success(): void
    {
        $eventName = 'Team1 vs Team2';
        $eventTypeId = 1;
        $eventDateTime = new DateTime();

        $coreEventType = new CoreEventType($eventTypeId, null, null);
        $coreEvent = new CoreEvent(null, $eventName, $eventDateTime, $coreEventType);
        $this->repository->add($coreEvent);

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);

        /** @var Event $event */
        $event = $records[0];
        $this->assertSame($eventName, $event->getName());
        $this->assertSame($eventDateTime, $event->getDateTime());
        $this->assertSame($eventTypeId, $event->getEventType()->getId());
    }

    public function testAdd_someBookmakers_success(): void
    {
        $eventName = 'Team1 vs Team2';
        $eventTypeId = 1;
        $eventDateTime = new DateTime();

        $coreEventType = new CoreEventType($eventTypeId, null, null);
        $coreEvent = new CoreEvent(null, $eventName, $eventDateTime, $coreEventType);
        $this->repository->add($coreEvent);
        $this->repository->add($coreEvent);
        $this->repository->add($coreEvent);

        $records = $this->repository->findAll();
        $this->assertCount(3, $records);

        /** @var Event $event */
        foreach ($records as $event) {
            $this->assertSame($eventName, $event->getName());
            $this->assertSame($eventDateTime, $event->getDateTime());
            $this->assertSame($eventTypeId, $event->getEventType()->getId());
        }
    }
}