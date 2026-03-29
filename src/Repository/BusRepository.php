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
        public function CountBus()
    {
        return $this->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->where('b.etat IN (:etat)')
        ->setParameter('etat', ['Incident','Chargement','En route'])
        ->getQuery()
        ->getSingleScalarResult();
    }
    public function findAllBusNotNull()
    {
        return $this->createQueryBuilder('b')
        ->where('b.administrateur IS NOT NULL')
        ->getQuery()
        ->getResult();
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
     public function findfilter(?string $zone, $trajet, ?string $statut)
{
    $qb = $this->createQueryBuilder('b')
               ->leftJoin('b.trajet', 't')
               ->addSelect('t');
    if ($zone) {
        $qb->andWhere('t.zone = :zone')
           ->setParameter('zone', $zone);
    }
    if ($trajet) {
        $qb->andWhere('b.trajet = :trajet')
           ->setParameter('trajet', $trajet);
    }
    if ($statut) {
        $qb->andWhere('b.etat = :etat')
           ->setParameter('etat', $statut);
    }
    $qb->orderBy('b.nom', 'ASC');
    return $qb->getQuery()->getResult();
}

public function searchBus(string $mot)
{
    $qb = $this->createQueryBuilder('b')
               ->leftJoin('b.trajet', 't')
               ->addSelect('t')
               ->leftJoin('t.id_point', 'p')
               ->addSelect('p');

    $qb->where('b.nom LIKE :q')
       ->orWhere('b.etat LIKE :q')
       ->orWhere('t.nom LIKE :q')
       ->orWhere('p.nom LIKE :q')
       ->setParameter('q', '%'.$mot.'%')
       ->orderBy('b.nom', 'ASC');

    return $qb->getQuery()->getResult();
}


public function findBusByUserFavoris(int $userId): array
{
    return $this->createQueryBuilder('b')
        ->join('b.trajet', 't')        // Bus → Trajet
        ->join('t.favoris', 'f')       // Trajet → Favori
        ->join('f.administrateur', 'u')          // Favori → User
        ->andWhere('u.id = :userId')
        ->setParameter('userId', $userId)
        ->orderBy('b.id', 'DESC')
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
