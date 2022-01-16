<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Controller;

use Auret\BetProfiler\Boundary\EventTypeBoundaryInterface;
use Auret\BetProfiler\Model\EventTypeRequest;
use Auret\MatchedBetting\Form\EventTypeType;
use Auret\MatchedBetting\Service\FlashMessageServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventTypeController extends AbstractMatchedBettingController
{
    private const CREATE_ROUTE_NAME = 'create_eventType';

    public function __construct(
        private EventTypeBoundaryInterface $eventTypeInteractor,
        private FlashMessageServiceInterface $flashMessageService,
    ) {}

    #[Route('/createEventType', name: self::CREATE_ROUTE_NAME)]
    public function create(): Response
    {
        return $this->render('eventType/details.html.twig', ['form' => $this->getFormView()]);
    }

    #[Route('/storeEventType', name: 'store_eventType')]
    public function store(Request $request): Response
    {
        $this->storeIfValid($this->getRequestDataEntity($request));
        return $this->redirectToRoute(self::CREATE_ROUTE_NAME);
    }

    private function storeIfValid(object $objectFromRequest): void
    {
        if (!$this->isValidData($objectFromRequest)) {
            $this->flashMessageService->addErrorMessage('create.new.event-type.flash.error');
            return;
        }

        $eventTypeRequest = new EventTypeRequest($objectFromRequest->name, $objectFromRequest->code);
        $this->eventTypeInteractor->add($eventTypeRequest);

        $this->flashMessageService->addInfoMessage(
            'create.new.event-type.flash.info',
            ['name' => $eventTypeRequest->getName(), 'code' => $eventTypeRequest->getCode()]
        );
    }

    protected function getClassType(): string
    {
        return EventTypeType::class;
    }

    private function isValidData(object $objectFromRequest): bool
    {
        if (empty($objectFromRequest->name) || empty($objectFromRequest->code)) {
            return false;
        }

        if (str_contains(' ', $objectFromRequest->code)) {
            return false;
        }

        return true;
    }
}
