<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractMatchedBettingController extends AbstractController
{
    private const FLASH_INFO = 'info';
    private const FLASH_WARN = 'warning';
    private const FLASH_ERROR = 'error';

    public function __construct(
        private TranslatorInterface $translator
    ) {}

    abstract protected function getClassType(): string;

    protected function getFormView(): FormView
    {
        return $this->getCreatedForm()->createView();
    }

    protected function getFormName(): string
    {
        return $this->getCreatedForm()->getName();
    }

    protected function getRequestDataEntity(Request $request): object
    {
        return (object) $request->get($this->getFormName());
    }

    protected function addInfoMessage(string $messageKey, array $parameters = []): void
    {
        $this->addFlashMessage(self::FLASH_INFO, $messageKey, $parameters);
    }

    protected function addWarningMessage(string $messageKey, array $parameters = []): void
    {
        $this->addFlashMessage(self::FLASH_WARN, $messageKey, $parameters);
    }

    protected function addErrorMessage(string $messageKey, array $parameters = []): void
    {
        $this->addFlashMessage(self::FLASH_ERROR, $messageKey, $parameters);
    }

    private function getCreatedForm(): FormInterface
    {
        return $this->createForm($this->getClassType());
    }

    private function addFlashMessage(string $messageType, string $messageKey, array $parameters): void
    {
        $message = $this->translator->trans($messageKey, $parameters);
        $this->addFlash($messageType, $message);
    }
}