<?php

namespace App\Repository;

use App\Entity\ImagesVoiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImagesVoiture>
 *
 * @method ImagesVoiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesVoiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesVoiture[]    findAll()
 * @method ImagesVoiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesVoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesVoiture::class);
    }

//    /**
//     * @return ImagesVoiture[] Returns an array of ImagesVoiture objects
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

//    public function findOneBySomeField($value): ?ImagesVoiture
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
