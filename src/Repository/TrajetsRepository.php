<?php

namespace App\Repository;

use App\Entity\Trajets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trajets>
 */
class TrajetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trajets::class);
    }
    
    public function CountTrajets()
    {
        return $this->createQueryBuilder('t')
        ->select('COUNT(t.id)')
        ->getQuery()
        ->getSingleScalarResult();
    }


    // recuperation du trajet du conducteur API
    public function findTrajetDriver(int $idbus)
    {
            return $this->createQueryBuilder('t')
            ->join('t.buses', 'b')
            ->where('b.id = :id')
            ->setParameter('id', $idbus)
            ->getQuery()
            ->getSingleResult();
    }

//    /**
//     * @return Trajets[] Returns an array of Trajets objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trajets
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
