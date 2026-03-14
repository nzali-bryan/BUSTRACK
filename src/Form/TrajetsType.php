<?php

namespace App\Form;

use App\Entity\Trajets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use App\Entity\Point;
use App\Repository\PointRepository;
use Dom\Entity;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TrajetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         
        $builder
            ->add('nom')
            ->add('etat' , ChoiceType::class, [
                'choices' => [
                    'Actif' => 'actif',
                    'Inactif' => 'inactif',
                ],])

            ->add('ligne')

            ->add('depart', EntityType::class, [
            'class' => Point::class,
            'choice_label' => 'nom',
            'mapped' => false,
            'placeholder' => 'Choisir le point de départ',
            'query_builder' => fn(PointRepository $repo) => $repo->createAvailablePointsQueryBuilder(),
        ])
        ->add('arrivee', EntityType::class, [
            'class' => Point::class,
            'choice_label' => 'nom',
            'mapped' => false, 
            'placeholder' => 'Choisir le point d’arrivée',
           'query_builder' => fn(PointRepository $repo) => $repo->createAvailablePointsQueryBuilder(),
        ]);
        
        ;
    
        

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajets::class,
        ]);
    }
}
