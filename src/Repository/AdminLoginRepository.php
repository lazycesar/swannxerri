<?php

namespace App\Repository;

use App\Entity\AdminLogin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminLogin|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminLogin|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminLogin[]    findAll()
 * @method AdminLogin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminLoginRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminLogin::class);
    }

    // /**
    //  * @return AdminLogin[] Returns an array of AdminLogin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminLogin
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
