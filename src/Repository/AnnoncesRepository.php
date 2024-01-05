<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonces>
 *
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }
 /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function search($mots = null, $type = null, $brand = null){
        $query = $this->createQueryBuilder('a');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.title, a.descriptions) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($type != null){
            $query->leftJoin('a.voiture', 'v')
            ->leftJoin('v.type', 'c');      
            $query->andWhere('c.id = :id')
                ->setParameter('id', $type);
        }
        $query->setMaxResults(10);
        return $query->getQuery()->getResult();
    }
//    /**
//     * @return Annonces[] Returns an array of Annonces objects
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
/*public function search($mots = null, $type = null ){
    $query = $this->createQueryBuilder('a');
    if($mots != null){
        $query->andWhere('MATCH_AGAINST(a.title, a.descriptions) AGAINST (:mots boolean) > 0')
            ->setParameter('mots', $mots);
    }
    if($type != null){
        $query->leftJoin('a.type', 'c');
        $query->andWhere('c.id = :id')
            ->setParameter('id', $type);
    }
   
    $query->orderBy('a.created_at', 'DESC');
    return $query->getQuery()->getResult();
}
*/


//    public function findOneBySomeField($value): ?Annonces
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
