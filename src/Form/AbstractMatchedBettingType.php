<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractMatchedBettingType extends AbstractType
{
    public function __construct(
        private RouterInterface $router,
        private TranslatorInterface $translator,
    ) {}

    abstract protected function getDataClass(): string;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => $this->getDataClass()]);
    }

    protected function generateAbsoluteUrl(string $urlName, array $parameters = []): string
    {
        return $this->router->generate($urlName, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    protected function translate(string $messageKey, array $parameters = []): string
    {
        return $this->translator->trans($messageKey, $parameters);
    }
}