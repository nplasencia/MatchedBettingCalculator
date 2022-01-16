<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Controller;

use Auret\BetProfiler\Boundary\MarketTypeBoundaryInterface;
use Auret\BetProfiler\Model\MarketTypeRequest;
use Auret\MatchedBetting\Form\MarketTypeType;
use Auret\MatchedBetting\Service\FlashMessageServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarketTypeController extends AbstractMatchedBettingController
{
    private const CREATE_ROUTE_NAME = 'create_marketType';

    public function __construct(
        private MarketTypeBoundaryInterface $marketTypeBoundary,
        private FlashMessageServiceInterface $flashMessageService,
    ) {}

    #[Route('/createMarketType', name: self::CREATE_ROUTE_NAME)]
    public function create(): Response
    {
        return $this->render('marketType/details.html.twig', ['form' => $this->getFormView()]);
    }

    #[Route('/storeMarketType', name: 'store_marketType')]
    public function store(Request $request): Response
    {
        $this->storeIfValid($this->getRequestDataEntity($request));
        return $this->redirectToRoute(self::CREATE_ROUTE_NAME);
    }

    private function storeIfValid(object $objectFromRequest): void
    {
        if (!$this->isValidData($objectFromRequest)) {
            $this->flashMessageService->addErrorMessage('create.new.market-type.flash.error');
            return;
        }

        $marketTypeRequest = new MarketTypeRequest($objectFromRequest->name, $objectFromRequest->code);
        $this->marketTypeBoundary->add($marketTypeRequest);

        $this->flashMessageService->addInfoMessage(
            'create.new.market-type.flash.info',
            ['name' => $marketTypeRequest->getName(), 'code' => $marketTypeRequest->getCode()]
        );
    }

    protected function getClassType(): string
    {
        return MarketTypeType::class;
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
