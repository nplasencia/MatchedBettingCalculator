<?php declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractMatchedBettingType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator
    ) {}

    abstract protected function getDataClass(): string;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => $this->getDataClass()]);
    }
}