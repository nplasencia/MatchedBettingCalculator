<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BackBetRepository::class)
 */
class BackBet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Bookmaker::class, inversedBy="backbet")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Bookmaker $bookmaker;

    /**
     * @ORM\Column(type="float")
     */
    private float $stake;

    /**
     * @ORM\Column(type="float")
     */
    private float $odds;

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
        Bookmaker $bookmaker = null,
        float $stake = null,
        float $odds = null,
        float $betReturn = null,
        float $profit = null,
        string $result = null
    ) {
        $this->id = $id;
        $this->bookmaker = $bookmaker;
        $this->stake = $stake;
        $this->odds = $odds;
        $this->return = $betReturn;
        $this->profit = $profit;
        $this->result = $result;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookmaker(): ?Bookmaker
    {
        return $this->bookmaker;
    }

    /**
     * @param Bookmaker|null $bookmaker
     */
    public function setBookmaker(?Bookmaker $bookmaker): void
    {
        $this->bookmaker = $bookmaker;
    }


    public function getStake(): ?float
    {
        return $this->stake;
    }

    public function getOdds(): ?float
    {
        return $this->odds;
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
