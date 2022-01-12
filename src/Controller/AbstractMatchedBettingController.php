<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractMatchedBettingController extends AbstractController
{
    abstract protected function getClassType(): string;

    protected function getFormView(): FormView
    {
        return $this->getCreatedForm()->createView();
    }

    private function getCreatedForm(): FormInterface
    {
        return $this->createForm($this->getClassType());
    }

    protected function getRequestDataEntity(Request $request): object
    {
        return (object) $request->get($this->getFormName());
    }

    private function getFormName(): string
    {
        return $this->getCreatedForm()->getName();
    }
}