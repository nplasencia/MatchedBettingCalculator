<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\Table(name="event")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $dateTime;

    /**
     * @ORM\ManyToOne(targetEntity=EventType::class, inversedBy="eventType")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?EventType $eventType;

    public function __construct(int $id = null, string $name = null, DateTime $dateTime = null, EventType $eventType = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateTime = $dateTime;
        $this->eventType = $eventType;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    public function getEventType(): ?EventType
    {
        return $this->eventType;
    }
}
