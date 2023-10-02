<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options:[
                'label' => 'Titre'
            ])
            ->add('description', options:[
                'label' => 'Description'
            ])
            ->add('beginAt', DateTimeType::class, [
                'label' => 'Date et heure de début',
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Date et heure de fin',
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('place', options:[
                'label' => 'Lieu de l\'évènement'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
