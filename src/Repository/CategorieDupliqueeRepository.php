<?php

namespace App\Repository;

use App\Entity\CategorieDupliquee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieDupliquee>
 *
 * @method CategorieDupliquee|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieDupliquee|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieDupliquee[]    findAll()
 * @method CategorieDupliquee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieDupliqueeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieDupliquee::class);
    }

//    /**
//     * @return CategorieDupliquee[] Returns an array of CategorieDupliquee objects
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

//    public function findOneBySomeField($value): ?CategorieDupliquee
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
