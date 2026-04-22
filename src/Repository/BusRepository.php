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
            ->setParameter('etat', ['Incident', 'Chargement', 'En route'])
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findAllBusNotNull()
    {
        return $this->createQueryBuilder('b')
            ->where('b.administrateur IS NOT NULL')
            ->andwhere('b.trajet IS NOT NULL')
            ->getQuery()
            ->getResult();
    }
    public function findZoneBus($zone)
    {
        return $this->createQueryBuilder('b')
            ->join('b.trajet', 't')
            ->where('t.zone= :zone')
            ->andwhere('b.administrateur IS NOT NULL')
            ->andwhere('b.trajet IS NOT NULL')
            ->setParameter('zone', $zone)
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
                ->andwhere('b.administrateur IS NOT NULL')
                ->andwhere('b.trajet IS NOT NULL')
                ->setParameter('trajet', $trajet);
        }
        if ($statut) {
            $qb->andWhere('b.etat = :etat')
                ->andwhere('b.administrateur IS NOT NULL')
                ->andwhere('b.trajet IS NOT NULL')
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
            ->andwhere('b.administrateur IS NOT NULL')
            ->andwhere('b.trajet IS NOT NULL')
            ->setParameter('q', '%' . $mot . '%')
            ->orderBy('b.nom', 'ASC');

        return $qb->getQuery()->getResult();
    }


    public function findBusByUserFavoris(int $userId): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.trajet', 't')        
            ->join('t.favoris', 'f')       
            ->join('f.administrateur', 'a')         
            ->andWhere('a.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('t.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

// recuperation du bus du conducteur API API

    public function findBusDriver(int $id)
    {
        return $this->createQueryBuilder('b')
            ->where('b.administrateur = :id')
            ->setParameter('id', $id)
            ->getQuery()
            // ->getResult();
            ->getSingleResult();
    }

    // recuperer le conducteurs 
        public function findDriverBus(int $id)
    {
        return $this->createQueryBuilder('b')
            ->where('b.administrateur = :id')
            ->setParameter('id', $id)
            ->getQuery()
            // ->getResult();
            ->getSingleResult();
    }

    public function findDriverByBus(int $busId)
{
    return $this->createQueryBuilder('b')
        ->select('b, a')
        ->leftJoin('b.administrateur', 'a')
        ->where('b.id = :busId')
        ->setParameter('busId', $busId)
        ->getQuery()
        ->getOneOrNullResult();
}


/**
 * Récupère tous les bus avec leurs relations Trajet et Administrateur (Conducteur)
 */
public function findAllWithRelations(): array
{
    return $this->createQueryBuilder('b')
        ->leftJoin('b.trajet', 't')
        ->leftJoin('b.administrateur', 'a')
        ->where('b.administrateur IS NOT NULL')
        ->andWhere('b.trajet IS NOT NUll')
        ->addSelect('t', 'a')  
        ->orderBy('t.nom', 'ASC')
        ->getQuery()
        ->getResult();
}

}
