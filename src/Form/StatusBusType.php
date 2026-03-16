<?php

namespace App\Form;

use App\Entity\Bus;
use App\Entity\Conducteurs;
use App\Entity\Trajets;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusBusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Inactif' => 'inactif',
                    'Chargement' => 'chargement',
                    'En route' => 'en_route',
                    'Incident' => 'incident',
                ],
                'placeholder' => 'Choisir l’état du bus',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bus::class,
        ]);
    }
}
