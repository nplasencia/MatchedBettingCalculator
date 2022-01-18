<?php declare(strict_types=1);

namespace Auret\MatchedBetting\Controller;

use Auret\BetProfiler\Boundary\MatchedBetBoundaryInterface;
use Auret\BetProfiler\Common\BetResultEnum;
use Auret\BetProfiler\Model\BackBetRequest;
use Auret\BetProfiler\Model\EventRequest;
use Auret\BetProfiler\Model\LayBetRequest;
use Auret\BetProfiler\Model\MatchedBetRequest;
use Auret\MatchedBetting\Form\MatchedBetType;
use Auret\MatchedBetting\Service\FlashMessageServiceInterface;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchedBetController extends AbstractMatchedBettingController
{
    private const CREATE_ROUTE_NAME = 'create_matchedBet';

    public function __construct(
        private FlashMessageServiceInterface $flashMessageService,
        private MatchedBetBoundaryInterface $matchedBetBoundary,
    ) {}

    #[Route('/createMatchedBet', name: self::CREATE_ROUTE_NAME)]
    public function create(): Response
    {
        return $this->render('matchedBet/details.html.twig', ['form' => $this->getFormView()]);
    }

    protected function getClassType(): string
    {
        return MatchedBetType::class;
    }

    #[Route('/storeMatchedBet', name: 'store_matchedBet')]
    public function store(Request $request): Response
    {
        $this->storeIfValid($this->getRequestDataEntity($request));
        return $this->redirectToRoute(self::CREATE_ROUTE_NAME);
    }

    private function storeIfValid(object $objectFromRequest): void
    {
        if (!$this->isValidData($objectFromRequest)) {
            //$this->flashMessageService->addErrorMessage('create.new.bookmaker.flash.error');
            return;
        }


        $matchedBetRequest = $this->getMatchedBetRequest($objectFromRequest);
        $this->matchedBetBoundary->add($matchedBetRequest);

//        $this->flashMessageService->addInfoMessage(
//            'create.new.bookmaker.flash.info',
//            ['name' => $bookmakerRequest->getName(), 'url' => $bookmakerRequest->getUrl()]
//        );
    }

    private function isValidData(object $objectFromRequest): bool
    {
        return true;
    }

    private function getMatchedBetRequest(object $objectFromRequest): MatchedBetRequest
    {
        $backBetRequest = $this->getBackBetRequestFromRequest($objectFromRequest);
        $layBetRequest = $this->getLayBetFromRequest($objectFromRequest);
        $eventRequest = $this->getEventFromRequest($objectFromRequest);

        return new MatchedBetRequest($backBetRequest, $layBetRequest, $eventRequest, $objectFromRequest->bet, intval($objectFromRequest->marketType), $objectFromRequest->betType, $objectFromRequest->betMode, $objectFromRequest->notes);
    }

    private function getBackBetRequestFromRequest(object $objectFromRequest): BackBetRequest
    {
        return new BackBetRequest(
            intval($objectFromRequest->bookmaker),
            floatval($objectFromRequest->backBetStake),
            floatval($objectFromRequest->backBetOdds),
            floatval($objectFromRequest->backBetReturn),
            floatval($objectFromRequest->backBetProfit),
            BetResultEnum::from($objectFromRequest->backBetResult)
        );
    }

    private function getLayBetFromRequest(object $objectFromRequest): LayBetRequest
    {
        return new LayBetRequest(
            intval($objectFromRequest->exchange),
            floatval($objectFromRequest->layBetStake),
            floatval($objectFromRequest->layBetOdds),
            floatval($objectFromRequest->layBetLiability),
            floatval($objectFromRequest->layBetReturn),
            floatval($objectFromRequest->layBetProfit),
            BetResultEnum::from($objectFromRequest->layBetResult)
        );
    }

    private function getEventFromRequest(object $objectFromRequest): EventRequest
    {
        $eventDate = $objectFromRequest->eventDateTime['date'];
        $eventTime = $objectFromRequest->eventDateTime['time'];
        $dateTime = new DateTime();
        $dateTime->setDate(intval($eventDate['year']), intval($eventDate['month']), intval($eventDate['day']));
        $dateTime->setTime(intval($eventTime['hour']), intval($eventTime['minute']));

        return new EventRequest($objectFromRequest->eventName, $dateTime, intval($objectFromRequest->eventType));
    }
}
