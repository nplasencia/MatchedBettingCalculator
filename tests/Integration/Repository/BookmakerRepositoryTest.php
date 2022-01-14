<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Integration\Repository;

use Auret\BetProfiler\Entity\Bookmaker;
use Auret\MatchedBetting\Repository\BookmakerRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BookmakerRepositoryTest extends KernelTestCase
{
    private BookmakerRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        /** @var BookmakerRepository $repository */
        $repository = $container->get(BookmakerRepository::class);
        $this->repository = $repository;
    }

    public function testAdd_oneBookmaker_success(): void
    {
        $bookmakerName = 'Some bookmaker Name';
        $bookmakerUrl = 'http://some.bookmaker.url';

        $bookmaker = new Bookmaker(null, $bookmakerName, $bookmakerUrl);
        $this->repository->add($bookmaker);

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertEquals($bookmakerName, $records[0]->getName());
        $this->assertEquals($bookmakerUrl, $records[0]->getUrl());
    }

    public function testAdd_someBookmakers_success(): void
    {
        $bookmakerName = 'Some bookmaker Name';
        $bookmakerUrl = 'http://some.bookmaker.url';

        $bookmaker = new Bookmaker(null, $bookmakerName, $bookmakerUrl);
        $this->repository->add($bookmaker);
        $this->repository->add($bookmaker);
        $this->repository->add($bookmaker);

        $records = $this->repository->findAll();
        $this->assertCount(3, $records);
        foreach ($records as $record) {
            $this->assertEquals($bookmakerName, $record->getName());
            $this->assertEquals($bookmakerUrl, $record->getUrl());
        }
    }
}