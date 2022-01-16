<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Application\Controller;

use Auret\MatchedBetting\Repository\EventTypeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class EventTypeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TranslatorInterface $translator;
    private EventTypeRepository $repository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $container = self::getContainer();
        $this->translator = $container->get(TranslatorInterface::class);
        $this->repository = $container->get(EventTypeRepository::class);
    }

    public function testCreate(): void
    {
        $this->client->request('GET', '/createEventType');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.event-type.title'));
    }

    public function testAdd_success(): void
    {
        $eventTypeName = 'Some Event Type name';
        $eventTypeCode = 'some_event_type_code';

        $this->client->followRedirects();

        $this->client->request('GET', '/createEventType');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['event_type[name]' => $eventTypeName, 'event_type[code]' => $eventTypeCode]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.event-type.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.event-type.flash.info', ['name' => $eventTypeName, 'code' => $eventTypeCode]),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(1, $records);
        $this->assertSame($eventTypeName, $records[0]->getName());
        $this->assertSame($eventTypeCode, $records[0]->getCode());
    }

    /**
     * @dataProvider addEventTypeShowsErrorDataProvider
     * @param string $eventTypeName
     * @param string $eventTypeCode
     */
    public function testAdd_withWrongInput_showsErrorFlashMessage(string $eventTypeName, string $eventTypeCode): void
    {
        $this->client->followRedirects();

        $this->client->request('GET', '/createEventType');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            ['event_type[name]' => $eventTypeName, 'event_type[code]' => $eventTypeCode]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.event-type.title'));
        $this->assertStringContainsString(
            $this->translator->trans('create.new.event-type.flash.error'),
            $this->client->getResponse()->getContent()
        );

        $records = $this->repository->findAll();
        $this->assertCount(0, $records);
    }

    private function addEventTypeShowsErrorDataProvider(): array
    {
        return [
            'Test empty event type name' => ['', 'some_code'],
            'Test empty event type code' => ['Some Name', ''],
            'Test empty event type name and code' => ['', ''],
            'Test event type code with spaces' => ['', 'some code with spaces'],
        ];
    }
}