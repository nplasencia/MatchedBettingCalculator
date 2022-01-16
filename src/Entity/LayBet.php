<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LayBetRepository::class)
 */
class LayBet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Exchange::class, inversedBy="laybet")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Exchange $exchange;

    /**
     * @ORM\Column(type="float")
     */
    private float $stake;

    /**
     * @ORM\Column(type="float")
     */
    private float $odds;

    /**
     * @ORM\Column(type="float")
     */
    private float $liability;

    /**
     * @ORM\Column(type="float", name="bet_return")
     */
    private float $return;

    /**
     * @ORM\Column(type="float")
     */
    private float $profit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $result;

    public function __construct(
        int $id = null,
        Exchange $exchange = null,
        float $stake = null,
        float $odds = null,
        float $liability = null,
        float $betReturn = null,
        float $profit = null,
        string $result = null
    ) {
        $this->id = $id;
        $this->exchange = $exchange;
        $this->stake = $stake;
        $this->odds = $odds;
        $this->liability = $liability;
        $this->return = $betReturn;
        $this->profit = $profit;
        $this->result = $result;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExchange(): ?Exchange
    {
        return $this->exchange;
    }

    /**
     * @param Exchange|null $exchange
     */
    public function setExchange(?Exchange $exchange): void
    {
        $this->exchange = $exchange;
    }


    public function getStake(): ?float
    {
        return $this->stake;
    }

    public function getOdds(): ?float
    {
        return $this->odds;
    }

    public function getLiability(): ?float
    {
        return $this->liability;
    }

    public function getReturn(): ?float
    {
        return $this->return;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }
}
