<?php

namespace App\Form;

use App\Entity\Administrateur;
use App\Entity\Bus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifDriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('username')
            // ->add('roles')
            ->add('password')
            ->add('telephone')
            ->add('bus', EntityType::class, [
                'class' => Bus::class,
                'choice_label' => 'nom',
                'placeholder'=> 'choisir son Bus',
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('b')
                        ->where('b.administrateur IS NULL'); // bus libres
                }  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Administrateur::class,
        ]);
    }
}
