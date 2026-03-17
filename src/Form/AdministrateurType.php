<?php

namespace App\Form;

use App\Entity\Administrateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class AdministrateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('username')
            ->add('roles',ChoiceType::class, [
                    'required' => false,
                    'choices' => [
                        "Administrateur de l'agence" => 'ROLE_ADMIN',
                        'Conducteur de bus' => 'ROLE_DRIVER',
                        'Autres' => 'ROLE_USER',
                    ],
        'expanded' => false,
        'multiple' => true,
            ])

            ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Administrateur::class,
        ]);
    }
}
