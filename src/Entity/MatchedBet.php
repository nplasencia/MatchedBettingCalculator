<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchedBetRepository::class)
 */
class MatchedBet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=BackBet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?BackBet $backBet;

    /**
     * @ORM\OneToOne(targetEntity=LayBet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?LayBet $layBet;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Event $event;

    /**
     * @ORM\ManyToOne(targetEntity=MarketType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?MarketType $marketType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $bet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $betType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $betMode;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $notes;

    public function __construct(
        ?int $id,
        ?BackBet $backBet,
        ?LayBet $layBet,
        ?Event $event,
        ?MarketType $marketType,
        ?string $bet,
        ?string $betType,
        ?string $betMode,
        ?string $notes
    ) {
        $this->id = $id;
        $this->backBet = $backBet;
        $this->layBet = $layBet;
        $this->event = $event;
        $this->marketType = $marketType;
        $this->bet = $bet;
        $this->betType = $betType;
        $this->betMode = $betMode;
        $this->notes = $notes;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBackBet(): ?BackBet
    {
        return $this->backBet;
    }

    public function getLayBet(): ?LayBet
    {
        return $this->layBet;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function getMarketType(): ?MarketType
    {
        return $this->marketType;
    }

    public function getBet(): ?string
    {
        return $this->bet;
    }

    public function getBetType(): ?string
    {
        return $this->betType;
    }

    public function getBetMode(): ?string
    {
        return $this->betMode;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
