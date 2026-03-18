<?php

namespace App\Form;

use App\Entity\Administrateur;
use App\Entity\Bus;
use App\Form\EntityRepository;

use App\Entity\Trajets;
// use App\Repository\ConducteursRepository;
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
                'administrateur',EntityType::class,[
                    'class'=> Administrateur::class,
                    'choice_label'=> 'nom',
                    'placeholder'=> 'choisir le conducteur',
                    'query_builder' => function ($repo) {
                        return $repo->createQueryBuilder('a')
                            ->leftJoin('a.bus', 'b')       
                            ->where('a.roles LIKE :role')
                            ->andWhere('b.id IS NULL')
                            ->setParameter('role', '%ROLE_DRIVER%');
                    },
                ]
            )
            // ->add(
            //     'administrateur',EntityType::class,[
            //     'class'=> Administrateur::class,
            //     'choice_label'=> 'nom',
            //     'placeholder'=> 'choisir le conducteur',
            //     ]
            // )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bus::class,
        ]);
    }
}
