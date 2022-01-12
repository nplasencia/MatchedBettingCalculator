<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\BetExchange;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BetExchangeType extends AbstractMatchedBettingType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('name', TextType::class)
            ->add('url', TextType::class)
            ->add('save', SubmitType::class, ['label' => $this->translator->trans('form.button.save')]);
    }

    protected function getDataClass(): string
    {
        return BetExchange::class;
    }
}
