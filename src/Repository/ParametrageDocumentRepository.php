<?php

namespace App\Repository;

use App\Entity\ParametrageDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParametrageDocument>
 *
 * @method ParametrageDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParametrageDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParametrageDocument[]    findAll()
 * @method ParametrageDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametrageDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParametrageDocument::class);
    }

    public function save(ParametrageDocument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParametrageDocument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ParametrageDocument[] Returns an array of ParametrageDocument objects
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

//    public function findOneBySomeField($value): ?ParametrageDocument
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
