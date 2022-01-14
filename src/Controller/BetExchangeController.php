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
        $this->storeIfValid($this->getRequestDataEntity($request));
        return $this->redirectToRoute(self::CREATE_ROUTE_NAME);
    }

    private function storeIfValid(object $objectFromRequest): void
    {
        if (!$this->isValidData($objectFromRequest)) {
            $this->flashMessageService->addErrorMessage('create.new.exchange.flash.error');
            return;
        }

        $exchangeRequest = new ExchangeRequest($objectFromRequest->name, $objectFromRequest->url);
        $this->exchangeInteractor->add($exchangeRequest);

        $this->flashMessageService->addInfoMessage(
            'create.new.exchange.flash.info',
            ['name' => $exchangeRequest->getName(), 'url' => $exchangeRequest->getUrl()]
        );
    }

    protected function getClassType(): string
    {
        return BetExchangeType::class;
    }

    private function isValidData(object $objectFromRequest): bool
    {
        if (empty($objectFromRequest->name) || empty($objectFromRequest->url)) {
            return false;
        }

        if (!$this->isUrlSchemaCorrect($objectFromRequest->url)) {
            return false;
        }

        return filter_var($objectFromRequest->url, FILTER_VALIDATE_URL) !== false;
    }

    private function isUrlSchemaCorrect(string $url): bool
    {
        return str_starts_with($url, 'http') || str_starts_with($url, 'https');
    }
}
