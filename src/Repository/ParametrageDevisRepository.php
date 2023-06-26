<?php

namespace App\Repository;

use App\Entity\ParametrageDevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParametrageDevis>
 *
 * @method ParametrageDevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParametrageDevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParametrageDevis[]    findAll()
 * @method ParametrageDevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametrageDevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParametrageDevis::class);
    }

    public function save(ParametrageDevis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParametrageDevis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ParametrageDevis[] Returns an array of ParametrageDevis objects
    */
   public function findByType($value): array
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.TypeDocument = :val')
           ->setParameter('val', $value)
           ->orderBy('p.id', 'ASC')
           ->setMaxResults(1)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?ParametrageDevis
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
