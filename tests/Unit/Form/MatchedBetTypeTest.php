<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Unit\Form;

use Auret\BetProfiler\Entity\MarketType;
use Auret\MatchedBetting\Entity\Bookmaker;
use Auret\MatchedBetting\Entity\EventType;
use Auret\MatchedBetting\Entity\Exchange;
use Auret\MatchedBetting\Form\MatchedBetType;
use Auret\MatchedBetting\Repository\BookmakerRepository;
use Auret\MatchedBetting\Repository\EventTypeRepository;
use Auret\MatchedBetting\Repository\ExchangeRepository;
use Auret\MatchedBetting\Repository\MarketTypeRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MatchedBetTypeTest extends TestCase
{
    private MatchedBetType $type;

    private RouterInterface $router;
    private TranslatorInterface $translator;
    private BookmakerRepository $bookmakerRepository;
    private ExchangeRepository $exchangeRepository;
    private EventTypeRepository $eventTypeRepository;
    private MarketTypeRepository $marketTypeRepository;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->bookmakerRepository = $this->createMock(BookmakerRepository::class);
        $this->exchangeRepository = $this->createMock(ExchangeRepository::class);
        $this->eventTypeRepository = $this->createMock(EventTypeRepository::class);
        $this->marketTypeRepository = $this->createMock(MarketTypeRepository::class);

        $this->type = new MatchedBetType(
            $this->bookmakerRepository,
            $this->exchangeRepository,
            $this->eventTypeRepository,
            $this->marketTypeRepository,
            $this->router,
            $this->translator
        );
    }

    public function testBuildForm_success(): void
    {
        $routeName = 'store_matchedBet';
        $generatedUrl = 'https://some.generated.url';
        $saveKey = 'form.button.save';
        $saveLabel = 'Some save translated label';

        $expectedBookmakers = [
            new Bookmaker(1, 'Some Bookmaker 1', 'https://some.bookmaker1url.com'),
            new Bookmaker(2, 'Some Bookmaker 2', 'https://some.bookmaker2url.com')
        ];
        $expectedExchanges = [
            new Exchange(1, 'Some Exchange 1', 'https://some.exchange1url.com'),
            new Exchange(2, 'Some Exchange 2', 'https://some.exchange2url.com')
        ];
        $expectedEventTypes = [
            new EventType(1, 'Some EventType1', 'some_event_type_code_1'),
            new EventType(2, 'Some EventType2', 'some_event_type_code_2'),
        ];
        $expectedMarketTypes = [
            new MarketType(1, 'Some MarketType1', 'some_market_type_code_1'),
            new MarketType(2, 'Some MarketType2', 'some_market_type_code_2'),
        ];

        $this->router->expects($this->once())->method('generate')
            ->with($routeName, [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn($generatedUrl);
        $this->bookmakerRepository->expects($this->once())->method('findAll')->willReturn($expectedBookmakers);
        $this->exchangeRepository->expects($this->once())->method('findAll')->willReturn($expectedExchanges);
        $this->eventTypeRepository->expects($this->once())->method('findAll')->willReturn($expectedEventTypes);
        $this->marketTypeRepository->expects($this->once())->method('findAll')->willReturn($expectedMarketTypes);
        $this->translator->expects($this->once())->method('trans')->with($saveKey, [])->willReturn($saveLabel);

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects($this->once())->method('setMethod')->with('POST')->willReturn($builder);
        $builder->expects($this->once())->method('setAction')->with($generatedUrl)->willReturn($builder);
        $builder->expects($this->exactly(22))->method('add')
            ->withConsecutive(
                ['bookmaker', ChoiceType::class, $this->anything()],
                ['backBetStake', TextType::class, ['required' => true]],
                ['backBetOdds', TextType::class, ['required' => true]],
                ['backBetReturn', TextType::class, ['required' => true]],
                ['backBetProfit', TextType::class, ['required' => true]],
                ['backBetResult', ChoiceType::class, $this->anything()],
                ['exchange', ChoiceType::class, $this->anything()],
                ['layBetStake', TextType::class, ['required' => true]],
                ['layBetOdds', TextType::class, ['required' => true]],
                ['layBetLiability', TextType::class, ['required' => true]],
                ['layBetReturn', TextType::class, ['required' => true]],
                ['layBetProfit', TextType::class, ['required' => true]],
                ['layBetResult', ChoiceType::class, $this->anything()],
                ['eventType', ChoiceType::class, $this->anything()],
                ['eventName', TextType::class, ['required' => true]],
                ['eventDateTime', DateTimeType::class, ['required' => true]],
                ['bet', TextType::class, ['required' => true]],
                ['marketType', ChoiceType::class, $this->anything()],
                ['betType', ChoiceType::class, $this->anything()],
                ['betMode', ChoiceType::class, $this->anything()],
                ['notes', TextType::class, ['required' => false]],
                ['save', SubmitType::class, ['label' => $saveLabel]],
            )
            ->willReturn($builder);

        $this->type->buildForm($builder, []);
    }
}
