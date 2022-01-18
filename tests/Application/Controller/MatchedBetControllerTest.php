<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Application\Controller;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\BetProfiler\Common\MatchedBetModeEnum;
use Auret\BetProfiler\Common\MatchedBetTypeEnum;
use Auret\MatchedBetting\Entity\BackBet;
use Auret\MatchedBetting\Entity\Event;
use Auret\MatchedBetting\Entity\LayBet;
use Auret\MatchedBetting\Entity\MatchedBet;
use Auret\MatchedBetting\Repository\BackBetRepository;
use Auret\MatchedBetting\Repository\EventRepository;
use Auret\MatchedBetting\Repository\LayBetRepository;
use Auret\MatchedBetting\Repository\MatchedBetRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MatchedBetControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    private TranslatorInterface $translator;
    private BackBetRepository $backBetRepository;
    private LayBetRepository $layBetRepository;
    private EventRepository $eventRepository;
    private MatchedBetRepository $matchedBetRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $container = self::getContainer();
        $this->translator = $container->get(TranslatorInterface::class);
        $this->backBetRepository = $container->get(BackBetRepository::class);
        $this->layBetRepository = $container->get(LayBetRepository::class);
        $this->eventRepository = $container->get(EventRepository::class);
        $this->matchedBetRepository = $container->get(MatchedBetRepository::class);
    }

    public function testCreate(): void
    {
        $this->client->request('GET', '/createMatchedBet');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.matched-bet.title'));
    }

    public function testAdd_backBetStore_success(): void
    {
        $bookmakerId = 1;
        $backBetStake = 100.2;
        $backBetOdds = 4.3;
        $backBetReturn = 125.19;
        $backBetProfit = -2.5;
        $backBetResult = BetResultEnum::WIN;
        $backBetResultValue = $backBetResult->value;

        $exchangeId = 1;
        $layBetStake = 100.2;
        $layBetOdds = 4.3;
        $layBetReturn = 125.19;
        $layBetProfit = -2.5;
        $layBetResult = BetResultEnum::LOSE;
        $layBetResultValue = $layBetResult->value;

        $eventTypeId = 1;
        $eventName = 'Team1 vs Team2';
        $eventDate = new DateTime();
        $eventDate->setTime(intval($eventDate->format('H')), intval($eventDate->format('i')));

        $bet = 'Win Team1';
        $marketTypeId = 1;
        $betType = MatchedBetTypeEnum::NORMAL->value;
        $betMode = MatchedBetModeEnum::STANDARD->value;
        $notes = 'These are some notes';

        $this->client->followRedirects();

        $this->client->request('GET', '/createMatchedBet');
        $this->client->submitForm(
            $this->translator->trans('form.button.save'),
            [
                'matched_bet[bookmaker]' => $bookmakerId,
                'matched_bet[backBetStake]' => $backBetStake,
                'matched_bet[backBetOdds]' => $backBetOdds,
                'matched_bet[backBetReturn]' => $backBetReturn,
                'matched_bet[backBetProfit]' => $backBetProfit,
                'matched_bet[backBetResult]' => $backBetResultValue,

                'matched_bet[exchange]' => $exchangeId,
                'matched_bet[layBetStake]' => $layBetStake,
                'matched_bet[layBetOdds]' => $layBetOdds,
                'matched_bet[layBetReturn]' => $layBetReturn,
                'matched_bet[layBetProfit]' => $layBetProfit,
                'matched_bet[layBetResult]' => $layBetResultValue,

                'matched_bet[eventType]' => $eventTypeId,
                'matched_bet[eventName]' => $eventName,
                'matched_bet[eventDateTime][date][month]' => $eventDate->format('m'),
                'matched_bet[eventDateTime][date][day]' => $eventDate->format('d'),
                'matched_bet[eventDateTime][date][year]' => $eventDate->format('o'),
                'matched_bet[eventDateTime][time][hour]' => $eventDate->format('H'),
                'matched_bet[eventDateTime][time][minute]' => $eventDate->format('i'),

                'matched_bet[bet]' => $bet,
                'matched_bet[marketType]' => $marketTypeId,
                'matched_bet[betType]' => $betType,
                'matched_bet[betMode]' => $betMode,
                'matched_bet[notes]' => $notes,
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', $this->translator->trans('create.new.matched-bet.title'));
//        $this->assertStringContainsString(
//            $this->translator->trans('create.new.bookmaker.flash.info', ['name' => $bookmakerName, 'url' => $bookmakerUrl]),
//            $this->client->getResponse()->getContent()
//        );

        $records = $this->backBetRepository->findAll();
        $this->assertCount(1, $records);
        /** @var BackBet $backBet */
        $backBet = $records[0];
        $this->assertSame($bookmakerId, $backBet->getBookmaker()->getId());
        $this->assertSame($backBetStake, $backBet->getStake());
        $this->assertSame($backBetOdds, $backBet->getOdds());
        $this->assertSame($backBetReturn, $backBet->getReturn());
        $this->assertSame($backBetProfit, $backBet->getProfit());
        $this->assertSame($backBetResultValue, $backBet->getResult());

        $records = $this->layBetRepository->findAll();
        $this->assertCount(1, $records);
        /** @var LayBet $layBet */
        $layBet = $records[0];
        $this->assertSame($exchangeId, $layBet->getExchange()->getId());
        $this->assertSame($layBetStake, $layBet->getStake());
        $this->assertSame($layBetOdds, $layBet->getOdds());
        $this->assertSame($layBetReturn, $layBet->getReturn());
        $this->assertSame($layBetProfit, $layBet->getProfit());
        $this->assertSame($layBetResultValue, $layBet->getResult());

        $records = $this->eventRepository->findAll();
        $this->assertCount(1, $records);
        /** @var Event $event */
        $event = $records[0];
        $this->assertSame($eventTypeId, $event->getEventType()->getId());
        $this->assertSame($eventName, $event->getName());
        $this->assertEquals($eventDate, $event->getDateTime());

        $records = $this->matchedBetRepository->findAll();
        $this->assertCount(1, $records);
        /** @var MatchedBet $matchedBet */
        $matchedBet = $records[0];
        $this->assertSame($backBet, $matchedBet->getBackBet());
        $this->assertSame($layBet, $matchedBet->getLayBet());
        $this->assertSame($event, $matchedBet->getEvent());
        $this->assertSame($bet, $matchedBet->getBet());
        $this->assertSame($marketTypeId, $matchedBet->getMarketType()->getId());
        $this->assertSame($betType, $matchedBet->getBetType());
        $this->assertSame($betMode, $matchedBet->getBetMode());
        $this->assertSame($notes, $matchedBet->getNotes());
    }

    /**
     * @dataProvider addBookmakerShowsErrorDataProvider
     * @param string $bookmakerName
     * @param string $bookmakerUrl
     */
    public function testAdd_withWrongInput_showsErrorFlashMessage(string $bookmakerName, string $bookmakerUrl): void
    {
        $this->markTestSkipped('Next TDD iteration');
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