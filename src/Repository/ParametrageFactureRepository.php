<?php

namespace App\Repository;

use App\Entity\ParametrageFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParametrageFacture>
 *
 * @method ParametrageFacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParametrageFacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParametrageFacture[]    findAll()
 * @method ParametrageFacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametrageFactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParametrageFacture::class);
    }

    public function save(ParametrageFacture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParametrageFacture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ParametrageFacture[] Returns an array of ParametrageFacture objects
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

//    public function findOneBySomeField($value): ?ParametrageFacture
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
