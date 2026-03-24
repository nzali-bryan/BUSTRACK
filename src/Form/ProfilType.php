<?php

namespace App\Form;

use App\Entity\Administrateur;
use App\Entity\Bus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('username')
                   ->add('currentPassword', PasswordType::class, [
            'mapped' => false,
            'required' => false,
            'label' => 'Ancien mot de passe',


        ])

        ->add('newPassword', PasswordType::class, [
            'mapped' => false,
            'required' => false,
            'label' => 'Nouveau mot de passe',

        ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Administrateur::class,
        ]);
    }
}
