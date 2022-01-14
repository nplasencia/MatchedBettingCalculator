<?php declare(strict_types=1);

namespace Auret\MatchedBetting\Controller;

use Auret\MatchedBetting\Form\BetBookmakerType;
use Auret\MatchedBetting\Service\FlashMessageServiceInterface;
use Auret\BetProfiler\Boundary\BookmakerBoundaryInterface;
use Auret\BetProfiler\Model\BookmakerRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BetBookmakerController extends AbstractMatchedBettingController
{
    private const CREATE_ROUTE_NAME = 'create_bookmaker';

    public function __construct(
        private BookmakerBoundaryInterface $bookmakerInteractor,
        private FlashMessageServiceInterface $flashMessageService,
    ) {}

    #[Route('/createBookmaker', name: self::CREATE_ROUTE_NAME)]
    public function create(): Response
    {
        return $this->render('Bookmaker/details.html.twig', ['form' => $this->getFormView()]);
    }

    #[Route('/storeBookmaker', name: 'store_bookmaker')]
    public function store(Request $request): Response
    {
        $this->storeIfValid($this->getRequestDataEntity($request));
        return $this->redirectToRoute(self::CREATE_ROUTE_NAME);
    }

    private function storeIfValid(object $objectFromRequest): void
    {
        if (!$this->isValidData($objectFromRequest)) {
            $this->flashMessageService->addErrorMessage('create.new.bookmaker.flash.error');
            return;
        }

        $bookmakerRequest = new BookmakerRequest($objectFromRequest->name, $objectFromRequest->url);
        $this->bookmakerInteractor->add($bookmakerRequest);

        $this->flashMessageService->addInfoMessage(
            'create.new.bookmaker.flash.info',
            ['name' => $bookmakerRequest->getName(), 'url' => $bookmakerRequest->getUrl()]
        );
    }

    protected function getClassType(): string
    {
        return BetBookmakerType::class;
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
