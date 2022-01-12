<?php declare(strict_types=1);

namespace Auret\MatchedBetting\Controller;

use Auret\MatchedBetting\Form\BetExchangeType;
use Auret\MatchedBetting\Service\FlashMessageServiceInterface;
use Auret\BetProfiler\Boundary\ExchangeBoundaryInterface;
use Auret\BetProfiler\Model\ExchangeRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetExchangeController extends AbstractMatchedBettingController
{
    private const CREATE_ROUTE_NAME = 'create_exchange';

    public function __construct(
        private ExchangeBoundaryInterface $exchangeInteractor,
        private FlashMessageServiceInterface $flashMessageService,
    ) {}

    #[Route('/createExchange', name: self::CREATE_ROUTE_NAME)]
    public function create(): Response
    {
        return $this->render('exchange/details.html.twig', ['form' => $this->getFormView()]);
    }

    #[Route('/storeExchange', name: 'store_exchange')]
    public function store(Request $request): Response
    {
        $exchangeRequest = $this->getExchangeRequest($request);
        $this->exchangeInteractor->add($exchangeRequest);

        $this->flashMessageService->addInfoMessage(
            'create.new.exchange.flash', ['name' => $exchangeRequest->getName(), 'url' => $exchangeRequest->getUrl()]
        );

        return $this->redirectToRoute(self::CREATE_ROUTE_NAME);
    }

    protected function getClassType(): string
    {
        return BetExchangeType::class;
    }

    private function getExchangeRequest(Request $request): ExchangeRequest
    {
        $betExchange = $this->getRequestDataEntity($request);
        return new ExchangeRequest($betExchange->name, $betExchange->url);
    }
}
