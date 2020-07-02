<?php

namespace App\Repository;

use App\Entity\SecretKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SecretKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecretKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecretKey[]    findAll()
 * @method SecretKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecretKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecretKey::class);
    }

    // /**
    //  * @return SecretKey[] Returns an array of SecretKey objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SecretKey
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
