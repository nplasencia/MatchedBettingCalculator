<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Form;

use Auret\MatchedBetting\Entity\EventType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EventTypeType extends AbstractMatchedBettingType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->setAction($this->generateAbsoluteUrl('store_eventType'))
            ->add('name', TextType::class, ['required' => true])
            ->add('code', TextType::class, ['required' => true])
            ->add('save', SubmitType::class, ['label' => $this->translate('form.button.save')]);
    }

    protected function getDataClass(): string
    {
        return EventType::class;
    }
}
