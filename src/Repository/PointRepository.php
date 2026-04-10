<?php

namespace App\Repository;

use App\Entity\Point;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Point>
 */
class PointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Point::class);
    }

//    /**
//     * @return Point[] Returns an array of Point objects
//     *//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Point
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function createAvailablePointsQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('tri')
                    ->where('tri.trajet IS NULL')
                    ->orderBy('tri.nom', 'ASC');
    }


    public function findByTrajetIdASC(int $trajetId): array
    {
        return $this->createQueryBuilder('p') 
            ->andWhere('p.trajet = :trajetId') 
            ->setParameter('trajetId', $trajetId)
            ->orderBy('p.nom', 'ASC') 
            ->setMaxResults(1)     
            ->getQuery()
            ->getResult();                  
    }

        public function findByTrajetIdDESC(int $trajetId): array
    {
        return $this->createQueryBuilder('p') 
            ->andWhere('p.trajet = :trajetId') 
            ->setParameter('trajetId', $trajetId)
            ->orderBy('p.nom', 'DESC')    
            ->setMaxResults(1)    
            ->getQuery()
            ->getResult();                  
    }

    public function CountPoints()
    {
        return $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->getQuery()
        ->getSingleScalarResult();
    }

    // recuperation du point du conducteur Api 
       public function findPointDriverASC(int $idTrajet)
    {
            return $this->createQueryBuilder('p')
            ->join('p.trajet', 't')
            ->where('t.id = :id')
            ->setParameter('id', $idTrajet)
            ->orderBy('p.nom','ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
       public function findPointDriverDESC(int $idTrajet)
    {
            return $this->createQueryBuilder('p')
            ->join('p.trajet', 't')
            ->where('t.id = :id')
            ->setParameter('id', $idTrajet)
            ->orderBy('p.nom','DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();;
    }

//     public function findPointsByTrajetId(int $trajetId): array
// {
//     return $this->createQueryBuilder('p') 
//         ->where('p.trajet = :trajetId')  
//         ->setParameter('trajetId', $trajetId)
//         ->orderBy('p.nom', 'ASC')    
//         ->getQuery()          
//         ->getResult();               
// }
}
// Retourne un QueryBuilder, pas un array

