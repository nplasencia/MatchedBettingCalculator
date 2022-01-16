<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookmakerRepository::class)
 */
class Bookmaker
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
     * @ORM\OneToMany(targetEntity=BackBet::class, mappedBy="bookmakerId", orphanRemoval=true)
     */
    private Collection $backBets;

    public function __construct(int $id = null, string $name = null, string $url = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->backBets = new ArrayCollection();
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
     * @return Collection|BackBet[]
     */
    public function getBackBets(): Collection
    {
        return $this->backBets;
    }

    public function addBackBet(BackBet $backBet): void
    {
        if ($this->backBets->contains($backBet)) {
            return;
        }
        $this->backBets[] = $backBet;
        $backBet->setBookmaker($this);
    }

    public function removeBackBet(BackBet $backBet): void
    {
        if (!$this->backBets->removeElement($backBet)) {
            return;
        }

        if ($backBet->getBookmaker() === $this) {
            $backBet->setBookmaker(null);
        }
    }
}
