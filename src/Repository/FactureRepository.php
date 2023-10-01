<?php

namespace App\Repository;

use App\Entity\Facture;
use App\Model\SearchFactureData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function save(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Facture[] Returns an array of Facture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
 /**
    * @return Facture[] Returns an array of Facture objects
    */
    public function findBySearch(SearchFactureData $search): array
    {
        $data = $this->createQueryBuilder('f')
           ->andWhere('f.Plusutilise = :val')
           ->leftJoin('f.Particulier', 'part')
           ->leftJoin('f.Professionnel', 'pro')
           ->setParameter('val', false)
           ->orderBy('f.id', 'ASC');

        if (!empty($search->NumFacture)){
            $data = $data 
                ->andwhere ('d.NumFacture LIKE :NumFacture')
                ->setParameter('NumFacture', "%{$search->NumFacture}%");
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
                ->andwhere ('f.PrixTTC >= :prixminTTC')
                ->setParameter('prixminTTC', $search->prixminTTC);
        }

        if (!empty($search->prixmaxTTC) || $search->prixmaxTTC >= 0){
            $data = $data 
                ->andwhere ('f.PrixTTC <= :prixmaxTTC')
                ->setParameter('prixmaxTTC', $search->prixmaxTTC);
        }

       return $data = $data->getQuery()->getResult();;
    }

    public function paginationQuery()
    {
       return $this->createQueryBuilder('f')
            ->andWhere('f.Plusutilise = :val')
            ->setParameter('val', false)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
       ;
    }
}
