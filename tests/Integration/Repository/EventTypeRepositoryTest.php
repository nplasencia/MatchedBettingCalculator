<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Entity\EventType;
use Auret\MatchedBetting\Repository\EventTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class EventTypeRepositoryTest extends KernelTestCase
{
    private EventTypeRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var EventTypeRepository $repository */
        $repository = $container->get(EventTypeRepository::class);
        $this->repository = $repository;
    }

    public function testAdd_oneEventType_success(): void
    {
        $eventTypeName = 'Some event type Name';
        $eventTypeCode = 'some_event_type_code';

        $eventType = new EventType(null, $eventTypeName, $eventTypeCode);
        $this->repository->add($eventType);

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertEquals($eventTypeName, $records[0]->getName());
        $this->assertEquals($eventTypeCode, $records[0]->getCode());
    }

    public function testAdd_someBookmakers_success(): void
    {
        $eventTypeName = 'Some event type Name';
        $eventTypeCode = 'some_event_type_code';

        $eventType = new EventType(null, $eventTypeName, $eventTypeCode);
        $this->repository->add($eventType);
        $this->repository->add($eventType);
        $this->repository->add($eventType);

        $records = $this->repository->findAll();
        $this->assertCount(3, $records);
        foreach ($records as $record) {
            $this->assertEquals($eventTypeName, $record->getName());
            $this->assertEquals($eventTypeCode, $record->getCode());
        }
    }
}