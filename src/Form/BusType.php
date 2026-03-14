<?php

namespace App\Form;

use App\Entity\Bus;
use App\Form\EntityRepository;
use App\Entity\Conducteurs;
use App\Entity\Trajets;
use App\Repository\ConducteursRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('capacite')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Inactif' => 'inactif',
                    'Chargement' => 'chargement',
                    'En route' => 'en_route',
                    'Incident' => 'incident',
                ],
                'placeholder' => 'Choisir l’état du bus',
            ])
            ->add('trajet', EntityType::class, [
                'class' => Trajets::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir le trajet',
            ])
            ->add(
                'conducteurs',EntityType::class,[
                    'class'=> Conducteurs::class,
                    'choice_label'=> 'nom',
                    'placeholder'=> 'choisir le conducteur',
                ]
            )
            ->add(
                'conducteurs',EntityType::class,[
                'class'=> Conducteurs::class,
                'choice_label'=> 'nom',
                'placeholder'=> 'choisir le conducteur',
                // 'query_builder' => function (ConducteursRepository $er) {
                //     return $er->createQueryBuilder('c')
                       
                //         ->leftJoin('c.bus', 'b')
                //         ->where('b.id IS NULL')
                //         ->orderBy('c.nom', 'ASC')
                //         ->getQuery()
                //         ->getResult();
                //     }


                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bus::class,
        ]);
    }
}
