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
            ->join('f.EtatDocument', 'ed')
            ->leftJoin('f.Particulier', 'part')
            ->leftJoin('f.Professionnel', 'pro')
            ->setParameter('val', false)
            ->orderBy('f.id', 'ASC');

        if (!empty($search->NumFacture)) {
            $data = $data
                ->andwhere('f.NumFacture LIKE :NumFacture')
                ->setParameter('NumFacture', "%{$search->NumFacture}%");
        }

        if (!empty($search->client)) {
            $data = $data
                ->andwhere("(concat(part.nom, ' ', part.prenom) LIKE :client) or 
                             (pro.nomsociete LIKE :client)")
                ->setParameter('client', "%{$search->client}%");
        }

        if (!empty($search->prixminTTC) && $search->prixminTTC > 0) {
            $data = $data
                ->andwhere('f.PrixTTC >= :prixminTTC')
                ->setParameter('prixminTTC', $search->prixminTTC);
        }

        if (!empty($search->prixmaxTTC) && $search->prixmaxTTC > 0) {
            $data = $data
                ->andwhere('f.PrixTTC <= :prixmaxTTC')
                ->setParameter('prixmaxTTC', $search->prixmaxTTC);
        }

        if (!empty($search->etatDocument)) {
            $data = $data
                ->andwhere('ed.id IN (:etat)')
                ->setParameter('etat', $search->etatDocument);
        }

        return $data = $data->getQuery()->getResult();;
    }
    /**
     * @return Facture[] Returns an array of Facture objects
     */
    public function findByRecherche(SearchFactureData $search): array
    {
        $data = $this->createQueryBuilder('f')
            ->andWhere('f.Plusutilise = :val')
            ->leftJoin('f.Particulier', 'part')
            ->leftJoin('f.Professionnel', 'pro')
            ->setParameter('val', false)
            ->orderBy('f.id', 'ASC');

        if (!empty($search->NumFacture)) {
            $data = $data
                ->andwhere('f.NumFacture LIKE :NumFacture')
                ->setParameter('NumFacture', "%{$search->NumFacture}%");
        }

        return $data = $data->getQuery()->getResult();;
    }

    public function paginationQuery()
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.Plusutilise = :val')
            ->setParameter('val', false)
            ->orderBy('f.id', 'ASC')
            ->getQuery();
    }

    public function findWithJoin(string $search): ?Facture
    {
        $data = $this->createQueryBuilder('f')
            ->select('f, part, pro, ad, af')
            ->leftJoin('f.Particulier', 'part')
            ->leftJoin('f.Professionnel', 'pro')
            ->leftJoin('f.AdresseChantier', 'ad')
            ->leftJoin('f.AdresseFacturation', 'af')
            ->andWhere('f.Plusutilise = false')
            ->andWhere('f.id = ' . $search)
            ->orderBy('f.DateFacture', 'ASC');

        return $data = $data->getQuery()->getOneOrNullResult();
    }

    public function findByIdAdresseChantier($value): ?Facture
    {
        return $this->createQueryBuilder('d')
            ->select('f, ad')
            ->innerJoin('f.AdresseChantier', 'ad')
            ->andWhere('ad.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByIdAdresseFacturation($value): ?Facture
    {
        return $this->createQueryBuilder('f')
            ->select('f, af')
            ->innerJoin('f.AdresseFacturation', 'af')
            ->andWhere('af.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
