<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('beginAt', options:[
                'label' => 'Date et heure de début'
            ])
            ->add('endAt', options:[
                'label' => 'Date et heure de fin'
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
