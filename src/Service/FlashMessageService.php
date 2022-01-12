<?php declare(strict_types = 1);

namespace App\Service;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlashMessageService implements FlashMessageServiceInterface
{
    private const FLASH_INFO = 'info';
    private const FLASH_WARN = 'warning';
    private const FLASH_ERROR = 'error';

    public function __construct(
        private ContainerInterface $container,
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
        $this->container->get('request_stack')->getSession()->getFlashBag()->add($messageType, $message);
    }
}