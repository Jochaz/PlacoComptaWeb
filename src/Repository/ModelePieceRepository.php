<?php

namespace App\Repository;

use App\Entity\ModelePiece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelePiece>
 *
 * @method ModelePiece|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelePiece|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelePiece[]    findAll()
 * @method ModelePiece[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelePieceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelePiece::class);
    }

    public function save(ModelePiece $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModelePiece $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ModelePiece[] Returns an array of ModelePiece objects
    */
   public function findByUse(): array
   {
       return $this->createQueryBuilder('m')
           ->andWhere('m.Plus_utilise = false')
           ->orderBy('m.Libelle', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

      /**
    * @return ModelePiece Returns an array of ModelePiece objects
    */
    public function findById($value): ?ModelePiece
    {
        return $this->createQueryBuilder('m')
            ->join('m.Materiaux', 'mat')
            ->andWhere('m.Plus_utilise = false')
            ->andWhere('mat.Plus_utilise = false')
            ->andWhere('m.id = :val')
            ->setParameter('val', $value)
            ->orderBy('mat.Designation', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    public function findOneBySomeField($value): ?ModelePiece
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
