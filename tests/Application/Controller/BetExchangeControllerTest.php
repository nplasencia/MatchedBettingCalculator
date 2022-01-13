<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Application\Controller;

use Auret\MatchedBetting\Repository\BetExchangeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BetExchangeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TranslatorInterface $translator;
    private BetExchangeRepository $repository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $container = self::getContainer();
        $this->translator = $container->get(TranslatorInterface::class);
        $this->repository = $container->get(BetExchangeRepository::class);
    }

    public function testCreate(): void
    {
        $this->client->request('GET', '/createExchange');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.exchange.title'));
    }

    public function testAdd_success(): void
    {
        $exchangeName = 'Some exchange name';
        $exchangeUrl = 'http://some.exchange.url';

        $this->client->followRedirects();

        $this->client->request('GET', '/createExchange');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['bet_exchange[name]' => $exchangeName, 'bet_exchange[url]' => $exchangeUrl]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.exchange.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.exchange.flash', ['name' => $exchangeName, 'url' => $exchangeUrl]),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertSame($exchangeName, $records[0]->getName());
        $this->assertSame($exchangeUrl, $records[0]->getUrl());
    }
}