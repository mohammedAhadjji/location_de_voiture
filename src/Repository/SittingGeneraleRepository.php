<?php

namespace App\Repository;

use App\Entity\SittingGenerale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SittingGenerale>
 *
 * @method SittingGenerale|null find($id, $lockMode = null, $lockVersion = null)
 * @method SittingGenerale|null findOneBy(array $criteria, array $orderBy = null)
 * @method SittingGenerale[]    findAll()
 * @method SittingGenerale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SittingGeneraleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SittingGenerale::class);
    }

//    /**
//     * @return SittingGenerale[] Returns an array of SittingGenerale objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SittingGenerale
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
