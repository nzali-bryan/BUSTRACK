<?php

namespace App\Repository;
use App\Entity\Trajets;
use App\Entity\Administrateur;
use App\Entity\Favoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favoris>
 */
class FavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoris::class);
    }


    public function findFavoris(int $userId): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.administrateur = :userId')
            ->setParameter('userId', $userId)
            ->join('f.trajets', 't') // pour récupérer les infos du trajet
            ->addSelect('t')
            ->orderBy('f.createdAt', 'DESC') // optionnel : du plus récent au plus ancien
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Favoris[] Returns an array of Favoris objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Favoris
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
