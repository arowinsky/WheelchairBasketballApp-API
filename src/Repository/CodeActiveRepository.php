<?php

namespace App\Repository;

use App\Entity\CodeActive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodeActive|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeActive|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeActive[]    findAll()
 * @method CodeActive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeActiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeActive::class);
    }

    // /**
    //  * @return CodeActive[] Returns an array of CodeActive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CodeActive
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
