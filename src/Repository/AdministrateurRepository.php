<?php

namespace App\Repository;

use App\Entity\Administrateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Administrateur>
 */
class AdministrateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrateur::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Administrateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // requete pour afficher les users qui ont le role driver 
    public function findDriver():array
    {
        return $this->createQueryBuilder('a')
        ->where('a.roles LIKE :role')
        ->orderBy('a.nom','ASC')
        ->setParameter('role', '%ROLE_DRIVER%' )
        ->getQuery()
        ->getResult();
    }
    public function findDriverNotBus():array
    {
        return $this->createQueryBuilder('a')
        ->leftJoin('a.bus' , 'b')
        ->Where('b.nom IS NULL')
        ->andWhere('a.roles LIKE :role')
        ->setParameter('role', '%ROLE_DRIVER%' )
        ->orderBy('a.nom','ASC')
        
        ->getQuery()
        ->getResult();
    }
    public function CountDrivers()
    {
        return $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->Where('a.roles like :role')
        ->setParameter('role','%ROLE_DRIVER%')
        ->getQuery()
        ->getSingleScalarResult();
    }
//  requete pour les drivers qui ont un bus

public function findDriverHaveBus()
{
        return $this->createQueryBuilder('a')
            ->innerJoin('a.bus', 'b')         
            // ->addSelect('b')                 
            ->orderBy('a.nom', 'ASC')           
            ->getQuery()
            ->getResult();
}



//    /**
//     * @return Administrateur[] Returns an array of Administrateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Administrateur
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
