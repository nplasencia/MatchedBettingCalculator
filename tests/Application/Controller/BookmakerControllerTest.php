<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Application\Controller;

use Auret\MatchedBetting\Repository\BookmakerRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BookmakerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TranslatorInterface $translator;
    private BookmakerRepository $repository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $container = self::getContainer();
        $this->translator = $container->get(TranslatorInterface::class);
        $this->repository = $container->get(BookmakerRepository::class);
    }

    public function testCreate(): void
    {
        $this->client->request('GET', '/createBookmaker');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.bookmaker.title'));
    }

    public function testAdd_success(): void
    {
        $bookmakerName = 'Some Bookmaker name';
        $bookmakerUrl = 'http://some.Bookmaker.url';

        $this->client->followRedirects();

        $this->client->request('GET', '/createBookmaker');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['bookmaker[name]' => $bookmakerName, 'bookmaker[url]' => $bookmakerUrl]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.bookmaker.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.bookmaker.flash.info', ['name' => $bookmakerName, 'url' => $bookmakerUrl]),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertSame($bookmakerName, $records[0]->getName());
        $this->assertSame($bookmakerUrl, $records[0]->getUrl());
    }

    /**
     * @dataProvider addBookmakerShowsErrorDataProvider
     * @param string $bookmakerName
     * @param string $bookmakerUrl
     */
    public function testAdd_withWrongInput_showsErrorFlashMessage(string $bookmakerName, string $bookmakerUrl): void
    {
        $this->client->followRedirects();

        $this->client->request('GET', '/createBookmaker');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['bookmaker[name]' => $bookmakerName, 'bookmaker[url]' => $bookmakerUrl]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.bookmaker.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.bookmaker.flash.error'),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(0, $records);
    }

    private function addBookmakerShowsErrorDataProvider(): array
    {
        return [
            'Test empty bookmaker name' => ['', 'http://some.bookmaker.url'],
            'Test empty bookmaker url' => ['Some bookmaker name', ''],
            'Test empty bookmaker name and url' => ['', ''],
            'Test malformed bookmaker url 1' => ['Some bookmaker name', 'w.com.https://'],
            'Test malformed bookmaker url 2' => ['Some bookmaker name', 'https:\\www.url.com'],
            'Test malformed bookmaker url 3' => ['Some bookmaker name', 'https:///www.url.com'],
            'Test malformed bookmaker url 4' => ['Some bookmaker name', 'htps://www.url.com'],
            'Test malformed bookmaker url 5' => ['Some bookmaker name', 'www.url.com'],
            'Test malformed bookmaker url 6' => ['Some bookmaker name', 'https//www.url.com'],
        ];
    }
}