<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractMatchedBettingController extends AbstractController
{
    private const FLASH_INFO = 'info';
    private const FLASH_WARN = 'warning';
    private const FLASH_ERROR = 'error';

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

    protected function addInfoMessage(string $message): void
    {
        $this->addFlash(self::FLASH_INFO, $message);
    }

    protected function addWarningMessage(string $message): void
    {
        $this->addFlash(self::FLASH_WARN, $message);
    }

    protected function addErrorMessage(string $message): void
    {
        $this->addFlash(self::FLASH_ERROR, $message);
    }

    private function getCreatedForm(): FormInterface
    {
        return $this->createForm($this->getClassType());
    }
}