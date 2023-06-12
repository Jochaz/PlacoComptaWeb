<?php

namespace App\Repository;

use App\Entity\Materiaux;
use App\Model\SearchData;
use App\Model\SearchDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Materiaux>
 *
 * @method Materiaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiaux[]    findAll()
 * @method Materiaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MateriauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiaux::class);
    }

    public function save(Materiaux $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Materiaux $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Materiaux[] Returns a query of Materiaux objects
    */
   public function paginationQuery()
   {
       return $this->createQueryBuilder('m')
           ->orderBy('m.Designation', 'ASC')
           ->andWhere('m.Plus_utilise = :val')
           ->setParameter('val', false)
           ->getQuery()
       ;
   }

    public function findBySearch(SearchData $searchData){
        $data =  $this->createQueryBuilder('m')
            ->orderBy('m.Designation', 'ASC')
            ->andWhere('m.Plus_utilise = :val')
            ->setParameter('val', false)
            ->addOrderby('m.Designation', 'asc');

        if (!empty($searchData->libelle)){
            $data = $data 
                ->andwhere ('m.Designation LIKE :libelle')
                ->setParameter('libelle', "%{$searchData->libelle}%");
        }

        if (!empty($searchData->categorie)){
            $cat = '';
            foreach ($searchData->categorie as $key => $value) {
                $cat = $cat.$value->getId().',';
            } 

            $cat = rtrim($cat, ",");
            $data = $data 
                ->join('m.Categorie', 'c')
                ->andwhere ('c.id IN ( :categories )')
                ->setParameter('categories', "{$cat}");
        }
        $data = $data->getQuery()->getResult();
        return $data;
    }

//    public function findOneBySomeField($value): ?Materiaux
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
