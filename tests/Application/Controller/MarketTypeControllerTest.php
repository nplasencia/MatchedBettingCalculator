<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Application\Controller;

use Auret\MatchedBetting\Repository\MarketTypeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MarketTypeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TranslatorInterface $translator;
    private MarketTypeRepository $repository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $container = self::getContainer();
        $this->translator = $container->get(TranslatorInterface::class);
        $this->repository = $container->get(MarketTypeRepository::class);
    }

    public function testCreate(): void
    {
        $this->client->request('GET', '/createMarketType');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.market-type.title'));
    }

    public function testAdd_success(): void
    {
        $marketTypeName = 'Some Market Type name';
        $marketTypeCode = 'some_market_type_code';

        $this->client->followRedirects();

        $this->client->request('GET', '/createMarketType');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['market_type[name]' => $marketTypeName, 'market_type[code]' => $marketTypeCode]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.market-type.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.market-type.flash.info', ['name' => $marketTypeName, 'code' => $marketTypeCode]),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertSame($marketTypeName, $records[0]->getName());
        $this->assertSame($marketTypeCode, $records[0]->getCode());
    }

    /**
     * @dataProvider addMarketTypeShowsErrorDataProvider
     * @param string $eventTypeName
     * @param string $eventTypeCode
     */
    public function testAdd_withWrongInput_showsErrorFlashMessage(string $eventTypeName, string $eventTypeCode): void
    {
        $this->client->followRedirects();

        $this->client->request('GET', '/createMarketType');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['market_type[name]' => $eventTypeName, 'market_type[code]' => $eventTypeCode]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.market-type.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.market-type.flash.error'),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(0, $records);
    }

    private function addMarketTypeShowsErrorDataProvider(): array
    {
        return [
            'Test empty market type name' => ['', 'some_code'],
            'Test empty market type code' => ['Some Name', ''],
            'Test empty market type name and code' => ['', ''],
            'Test market type code with spaces' => ['', 'some code with spaces'],
        ];
    }
}