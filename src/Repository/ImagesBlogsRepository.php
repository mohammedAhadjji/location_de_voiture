<?php

namespace App\Repository;

use App\Entity\ImagesBlogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImagesBlogs>
 *
 * @method ImagesBlogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesBlogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesBlogs[]    findAll()
 * @method ImagesBlogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesBlogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesBlogs::class);
    }

//    /**
//     * @return ImagesBlogs[] Returns an array of ImagesBlogs objects
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

//    public function findOneBySomeField($value): ?ImagesBlogs
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
