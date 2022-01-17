<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Form;

use Auret\BetProfiler\Common\BetResultEnum;
use Auret\BetProfiler\Common\MatchedBetModeEnum;
use Auret\BetProfiler\Common\MatchedBetTypeEnum;
use Auret\MatchedBetting\Entity\Bookmaker;
use Auret\MatchedBetting\Entity\EventType;
use Auret\MatchedBetting\Entity\Exchange;
use Auret\MatchedBetting\Entity\MatchedBet;
use Auret\MatchedBetting\Entity\MarketType;
use Auret\MatchedBetting\Repository\BookmakerRepository;
use Auret\MatchedBetting\Repository\EventTypeRepository;
use Auret\MatchedBetting\Repository\ExchangeRepository;
use Auret\MatchedBetting\Repository\MarketTypeRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MatchedBetType extends AbstractMatchedBettingType
{
    public function __construct(
        private BookmakerRepository $bookmakerRepository,
        private ExchangeRepository $exchangeRepository,
        private EventTypeRepository $eventTypeRepository,
        private MarketTypeRepository $marketTypeRepository,
        protected RouterInterface $router,
        protected TranslatorInterface $translator
    ) {
        parent::__construct($router, $translator);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->setAction($this->generateAbsoluteUrl('store_matchedBet'))

            ->add('bookmaker', ChoiceType::class, $this->getBookmakerChoices())
            ->add('backBetStake', TextType::class, ['required' => true])
            ->add('backBetOdds', TextType::class, ['required' => true])
            ->add('backBetReturn', TextType::class, ['required' => true])
            ->add('backBetProfit', TextType::class, ['required' => true])
            ->add('backBetResult', ChoiceType::class, $this->getResultChoices())

            ->add('exchange', ChoiceType::class, $this->getExchangeChoices())
            ->add('layBetStake', TextType::class, ['required' => true])
            ->add('layBetOdds', TextType::class, ['required' => true])
            ->add('layBetLiability', TextType::class, ['required' => true])
            ->add('layBetReturn', TextType::class, ['required' => true])
            ->add('layBetProfit', TextType::class, ['required' => true])
            ->add('layBetResult', ChoiceType::class, $this->getResultChoices())

            ->add('eventType', ChoiceType::class, $this->getEventTypeChoices())
            ->add('eventName', TextType::class, ['required' => true])
            ->add('eventDateTime', DateTimeType::class, ['required' => true])

            ->add('bet', TextType::class, ['required' => true])
            ->add('marketType', ChoiceType::class, $this->getMarketTypeChoices())
            ->add('betType', ChoiceType::class, $this->getBetTypeChoices())
            ->add('betMode', ChoiceType::class, $this->getBetModeChoices())
            ->add('notes', TextType::class, ['required' => false])

            ->add('save', SubmitType::class, ['label' => $this->translate('form.button.save')]);
    }

    protected function getDataClass(): string
    {
        return MatchedBet::class;
    }

    private function getBookmakerChoices(): array
    {
        return [
            'choices' => $this->bookmakerRepository->findAll(),
            'choice_value' => function (?Bookmaker $bookmaker) {
                return $bookmaker ? $bookmaker->getId() : '';
            },
            'choice_label' => function (?Bookmaker $bookmaker) {
                return $bookmaker ? $bookmaker->getName() : '';
            },
            'required' => true,
        ];
    }

    private function getResultChoices(): array
    {
        return [
            'choices' => BetResultEnum::cases(),
            'choice_value' => function (?BetResultEnum $result) {
                return $result ? $result->value : '';
            },
            'choice_label' => function (?BetResultEnum $result) {
                return $result ? $result->value : '';
            },
            'required' => true,
        ];
    }

    private function getExchangeChoices(): array
    {
        return [
            'choices' => $this->exchangeRepository->findAll(),
            'choice_value' => function (?Exchange $exchange) {
                return $exchange ? $exchange->getId() : '';
            },
            'choice_label' => function (?Exchange $exchange) {
                return $exchange ? $exchange->getName() : '';
            },
            'required' => true,
        ];
    }

    private function getEventTypeChoices(): array
    {
        return [
            'choices' => $this->eventTypeRepository->findAll(),
            'choice_value' => function (?EventType $eventType) {
                return $eventType ? $eventType->getId() : '';
            },
            'choice_label' => function (?EventType $eventType) {
                return $eventType ? $eventType->getName() : '';
            },
            'required' => true,
        ];
    }

    private function getMarketTypeChoices(): array
    {
        return [
            'choices' => $this->marketTypeRepository->findAll(),
            'choice_value' => function (?MarketType $marketType) {
                return $marketType ? $marketType->getId() : '';
            },
            'choice_label' => function (?MarketType $marketType) {
                return $marketType ? $marketType->getName() : '';
            },
            'required' => true,
        ];
    }

    private function getBetTypeChoices(): array
    {
        return [
            'choices' => MatchedBetTypeEnum::cases(),
            'choice_value' => function (?MatchedBetTypeEnum $betType) {
                return $betType ? $betType->value : '';
            },
            'choice_label' => function (?MatchedBetTypeEnum $betType) {
                return $betType ? $betType->value : '';
            },
            'required' => true,
        ];
    }

    private function getBetModeChoices(): array
    {
        return [
            'choices' => MatchedBetModeEnum::cases(),
            'choice_value' => function (?MatchedBetModeEnum $betMode) {
                return $betMode ? $betMode->value : '';
            },
            'choice_label' => function (?MatchedBetModeEnum $betMode) {
                return $betMode ? $betMode->value : '';
            },
            'required' => true,
        ];
    }
}
