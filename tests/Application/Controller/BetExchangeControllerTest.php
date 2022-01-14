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
            $this->translator->trans('create.new.exchange.flash.info', ['name' => $exchangeName, 'url' => $exchangeUrl]),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertSame($exchangeName, $records[0]->getName());
        $this->assertSame($exchangeUrl, $records[0]->getUrl());
    }

    /**
     * @dataProvider addExchangeShowsErrorDataProvider
     * @param string $exchangeName
     * @param string $exchangeUrl
     */
    public function testAdd_withWrongInput_showsErrorFlashMessage(string $exchangeName, string $exchangeUrl): void
    {
        $this->client->followRedirects();

        $this->client->request('GET', '/createExchange');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['bet_exchange[name]' => $exchangeName, 'bet_exchange[url]' => $exchangeUrl]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.exchange.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.exchange.flash.error'),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(0, $records);
    }

    private function addExchangeShowsErrorDataProvider(): array
    {
        return [
            'Test empty exchange name' => ['', 'http://some.exchange.url'],
            'Test empty exchange url' => ['Some exchange name', ''],
            'Test empty exchange name and url' => ['', ''],
            'Test malformed exchange url 1' => ['Some exchange name', 'w.com.https://'],
            'Test malformed exchange url 2' => ['Some exchange name', 'https:\\www.url.com'],
            'Test malformed exchange url 3' => ['Some exchange name', 'https:///www.url.com'],
            'Test malformed exchange url 4' => ['Some exchange name', 'htps://www.url.com'],
            'Test malformed exchange url 5' => ['Some exchange name', 'www.url.com'],
            'Test malformed exchange url 6' => ['Some exchange name', 'https//www.url.com'],
        ];
    }
}