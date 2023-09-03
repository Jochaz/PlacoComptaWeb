<?php

namespace App\Repository;

use App\Entity\Devis;
use App\Model\SearchDevisData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devis>
 *
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    public function save(Devis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Devis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Devis[] Returns an array of Devis objects
    */
    public function findBySearch(SearchDevisData $search): array
    {
        $data = $this->createQueryBuilder('d')
           ->andWhere('d.Plusutilise = :val')
           ->leftJoin('d.Particulier', 'part')
           ->leftJoin('d.Professionnel', 'pro')
           ->setParameter('val', false)
           ->orderBy('d.id', 'ASC');

        if (!empty($search->numDevis)){
            $data = $data 
                ->andwhere ('d.NumDevis LIKE :NumDevis')
                ->setParameter('NumDevis', "%{$search->numDevis}%");
        }

        if (!empty($search->client)){
            dump($search->client);
            $data = $data 
                ->andwhere ('(part.nom LIKE :client) or 
                             (part.prenom LIKE :client) or
                             (pro.nomsociete LIKE :client)')
                ->setParameter('client', "%{$search->client}%");
        }

        if (!empty($search->prixminTTC) || $search->prixminTTC >= 0){
            $data = $data 
                ->andwhere ('d.PrixTTC >= :prixminTTC')
                ->setParameter('prixminTTC', $search->prixminTTC);
        }

        if (!empty($search->prixmaxTTC) || $search->prixmaxTTC >= 0){
            $data = $data 
                ->andwhere ('d.PrixTTC <= :prixmaxTTC')
                ->setParameter('prixmaxTTC', $search->prixmaxTTC);
        }

       return $data = $data->getQuery()->getResult();;
    }

    public function paginationQuery()
    {
       return $this->createQueryBuilder('d')
            ->andWhere('d.Plusutilise = :val')
            ->setParameter('val', false)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
       ;
    }

    public function findWithJoin(string $search): ?Devis
    {
        $data = $this->createQueryBuilder('d')
           ->select('d, part, pro, ad, af')
           ->leftJoin('d.Particulier', 'part')
           ->leftJoin('d.Professionnel', 'pro')
           ->leftJoin('d.AdresseChantier', 'ad')
           ->leftJoin('d.AdresseFacturation', 'af')
           ->andWhere('d.Plusutilise = false')
           ->andWhere('d.id = '.$search)
           ->orderBy('d.DateDevis', 'ASC');

       return $data = $data->getQuery()->getOneOrNullResult();
    }


//    public function findOneBySomeField($value): ?Devis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
