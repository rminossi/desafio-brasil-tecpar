<?php

namespace App\Repository;

use App\Entity\Hit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hit[]    findAll()
 * @method Hit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hit::class);
    }

    // /**
    //  * @return Hit[] Returns an array of Hit objects
    //  */

    public function findByMaxAttempts($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.attempts <= :val')
            ->setParameter('val', $value ?? 99999999999)
            ->orderBy('h.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Hit
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
