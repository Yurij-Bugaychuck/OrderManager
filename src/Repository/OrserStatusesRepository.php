<?php

namespace App\Repository;

use App\Entity\OrserStatuses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrserStatuses|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrserStatuses|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrserStatuses[]    findAll()
 * @method OrserStatuses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrserStatusesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrserStatuses::class);
    }

    // /**
    //  * @return OrserStatuses[] Returns an array of OrserStatuses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrserStatuses
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
