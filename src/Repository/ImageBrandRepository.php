<?php

namespace App\Repository;

use App\Entity\ImageBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageBrand>
 *
 * @method ImageBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageBrand[]    findAll()
 * @method ImageBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageBrand::class);
    }

//    /**
//     * @return ImageBrand[] Returns an array of ImageBrand objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageBrand
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
