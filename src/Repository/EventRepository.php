<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Repository;

use Auret\BetProfiler\Entity\Event;
use Auret\BetProfiler\Gateway\EventGatewayInterface;

class EventRepository implements EventGatewayInterface
{
    public function add(Event $event): Event
    {
        // TODO: Implement add() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}