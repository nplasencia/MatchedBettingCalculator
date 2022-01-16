<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExchangeRepository::class)
 */
class Exchange
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
     * @ORM\Column(type="string", length=255)
     */
    private ?string $url;

    /**
     * @ORM\OneToMany(targetEntity=LayBet::class, mappedBy="exchangeId", orphanRemoval=true)
     */
    private Collection $layBets;

    public function __construct(int $id = null, string $name = null, string $url = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->layBets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return Collection|LayBet[]
     */
    public function getLayBets(): Collection
    {
        return $this->layBets;
    }

    public function addLayBet(LayBet $layBet): void
    {
        if ($this->layBets->contains($layBet)) {
            return;
        }
        $this->layBets[] = $layBet;
        $layBet->setExchange($this);
    }

    public function removeBackBet(LayBet $layBet): void
    {
        if (!$this->layBets->removeElement($layBet)) {
            return;
        }

        if ($layBet->getExchange() === $this) {
            $layBet->setExchange(null);
        }
    }
}
