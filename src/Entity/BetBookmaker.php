<?php declare(strict_types=1);

namespace Auret\MatchedBetting\Entity;

use Auret\MatchedBetting\Repository\BookmakerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookmakerRepository::class)
 * @ORM\Table(name="bookmaker")
 */
class BetBookmaker
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

    public function __construct(int $id = null, string $name = null, string $url = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
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
}
