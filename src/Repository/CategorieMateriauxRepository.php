<?php

namespace App\Repository;

use App\Entity\CategorieMateriaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieMateriaux>
 *
 * @method CategorieMateriaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieMateriaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieMateriaux[]    findAll()
 * @method CategorieMateriaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieMateriauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieMateriaux::class);
    }

    public function save(CategorieMateriaux $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategorieMateriaux $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CategorieMateriaux[] Returns an array of CategorieMateriaux objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieMateriaux
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
