<?php

namespace App\Repository;

use App\Entity\Materiaux;
use App\Entity\Devis;
use App\Entity\Facture;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

   public function findByUse(): array
   {
        return $this->createQueryBuilder('m')
           ->andWhere('m.Plus_utilise = false')
           ->orderBy('m.Designation', 'ASC')
           ->getQuery()
           ->getResult()
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
            $data = $data 
                ->join('m.Categorie', 'c')
                ->andwhere ('c.id IN ( :categories )')
                ->setParameter('categories', $searchData->categorie);
        }
        if (!empty($searchData->prixminachat) && $searchData->prixminachat != 0) {
            $data = $data 
            ->andwhere ('m.PrixAchat >= :prixminachat')
            ->setParameter('prixminachat', "{$searchData->prixminachat}");
        }

        if (!empty($searchData->prixmaxachat) && $searchData->prixmaxachat != 0) {
            $data = $data 
            ->andwhere ('m.PrixAchat <= :prixmaxachat')
            ->setParameter('prixmaxachat', "{$searchData->prixmaxachat}");
        }

        
        if (!empty($searchData->prixminachat) && $searchData->prixminunitaire != 0) {
            $data = $data 
            ->andwhere ('m.PrixUnitaire >= :prixminunitaire')
            ->setParameter('prixminunitaire', "{$searchData->prixminunitaire}");
        }

        if (!empty($searchData->prixmaxachat && $searchData->prixmaxunitaire != 0)) {
            $data = $data 
            ->andwhere ('m.PrixUnitaire <= :prixmaxunitaire')
            ->setParameter('prixmaxunitaire', "{$searchData->prixmaxunitaire}");
        }


        $data = $data->getQuery()->getResult();
        return $data;
    }

    public function findByMateriauxManquantDevis($value)
    {
        //Il faut retourner tout les materiaux sauf ceux déjà présent dans le devis !
        $expr = $this->_em->getExpressionBuilder();

        $sub = $this->_em->createQueryBuilder()
                ->select('m2.id')
                ->from(Devis::class, 'd')
                ->innerJoin('d.ligneDevis', 'ld')
                ->innerJoin('ld.Materiaux', 'm2')
                ->andWhere('d.id = '.$value)
                ;

        $query = $this->createQueryBuilder('m')
                ->Where($expr->notIn('m.id', $sub->getDQL()))
                ->andWhere('m.Plus_utilise = false')
                ->orderBy('m.Designation');
                ;

        return $query->getQuery()->getResult();
    }
    public function findByMateriauxManquantFacture($value)
    {
        //Il faut retourner tout les materiaux sauf ceux déjà présent dans la facture !
        $expr = $this->_em->getExpressionBuilder();

        $sub = $this->_em->createQueryBuilder()
                ->select('m2.id')
                ->from(Facture::class, 'f')
                ->innerJoin('f.LigneFacture', 'lf')
                ->innerJoin('lf.Materiaux', 'm2')
                ->andWhere('f.id = '.$value)
                ;

        $query = $this->createQueryBuilder('m')
                ->Where($expr->notIn('m.id', $sub->getDQL()))
                ->andWhere('m.Plus_utilise = false')
                ;

        return $query->getQuery()->getResult();
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
