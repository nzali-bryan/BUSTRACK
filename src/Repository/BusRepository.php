<?php

namespace App\Repository;

use App\Entity\Bus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bus>
 */
class BusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bus::class);
    }
    public function findBusActifs(array $etat)
    {
        return $this->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->where('b.etat IN (:etat)')
        ->setParameter('etat', $etat)
        ->getQuery()
        ->getSingleScalarResult();
    }
    public function findZoneBus($zone)
    {
        return $this->createQueryBuilder('b')
            ->join('b.trajet','t')
            ->where('t.zone= :zone')
            ->setParameter('zone',$zone)
            ->getQuery()
            ->getResult();

    }
//    /**
//     * @return Bus[] Returns an array of Bus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bus
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
