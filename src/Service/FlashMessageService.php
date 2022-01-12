<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashMessageService implements FlashMessageServiceInterface
{
    private const FLASH_INFO = 'info';
    private const FLASH_WARN = 'warning';
    private const FLASH_ERROR = 'error';

    public function __construct(
        private RequestStack $requestStack,
        private TranslatorInterface $translator,
    ) {}

    public function addInfoMessage(string $messageKey, array $parameters = []): void
    {
        $this->addFlashMessage(self::FLASH_INFO, $messageKey, $parameters);
    }

    public function addWarningMessage(string $messageKey, array $parameters = []): void
    {
        $this->addFlashMessage(self::FLASH_WARN, $messageKey, $parameters);
    }

    public function addErrorMessage(string $messageKey, array $parameters = []): void
    {
        $this->addFlashMessage(self::FLASH_ERROR, $messageKey, $parameters);
    }

    private function addFlashMessage(string $messageType, string $messageKey, array $parameters): void
    {
        $message = $this->translator->trans($messageKey, $parameters);
        $this->requestStack->getSession()->getFlashBag()->add($messageType, $message);
    }
}