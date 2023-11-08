<?php

namespace App\Repository;

use App\Entity\Professionnel;
use App\Model\SearchDataProfessionnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Professionnel>
 *
 * @method Professionnel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Professionnel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Professionnel[]    findAll()
 * @method Professionnel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Professionnel::class);
    }

    public function save(Professionnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Professionnel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginationQuery()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nomsociete', 'ASC')
            ->andWhere('p.actif = :val')
            ->setParameter('val', true)
            ->getQuery()
        ;
    }

    public function findBySearch(SearchDataProfessionnel $searchData){
        $data =  $this->createQueryBuilder('p')
            ->andWhere('p.actif = :val')
            ->setParameter('val', true)
            ->addOrderby('p.nomsociete', 'asc');

        if (!empty($searchData->nomSociete)){
            $data = $data 
                ->andwhere ('p.nomsociete LIKE :nomsociete')
                ->setParameter('nomsociete', "%{$searchData->nomSociete}%");
        }
        if (!empty($searchData->SIRET)){
            $data = $data 
                ->andwhere ('p.SIRET LIKE :SIRET')
                ->setParameter('SIRET', "%{$searchData->SIRET}%");
        }
        if (!empty($searchData->SIREN)){
            $data = $data 
                ->andwhere ('p.SIREN LIKE :SIREN')
                ->setParameter('SIREN', "%{$searchData->SIREN}%");
        }

        if (!empty($searchData->adresseemail)){
            $data = $data 
                ->andwhere ('p.adresseemail1 LIKE :adresseemail')
                ->setParameter('adresseemail', "%{$searchData->adresseemail}%");
        }

        if (!empty($searchData->numerotelephoneportable)){
            $data = $data 
                ->andwhere ('p.numerotelephoneportable1 LIKE :numerotelephoneportable')
                ->setParameter('numerotelephoneportable', "%{$searchData->numerotelephoneportable}%");
        }

        if (!empty($searchData->numerotelephonefixe)){
            $data = $data 
                ->andwhere ('p.numerotelephonefixe1 LIKE :numerotelephonefixe')
                ->setParameter('numerotelephonefixe', "%{$searchData->numerotelephonefixe}%");
        }

        $data = $data->getQuery()->getResult();
        return $data;
    }

    public function findByRecherche(string $searchData){
        $data =  $this->createQueryBuilder('p')
            ->andWhere('p.actif = :val')
            ->setParameter('val', true)
            ->addOrderby('p.nomsociete', 'asc');

        if (!empty($searchData)){
            $data = $data 
                ->andwhere ('p.nomsociete LIKE :nomsociete')
                ->setParameter('nomsociete', "%{$searchData}%");
        }

        $data = $data->getQuery()->getResult();
        return $data;
    }

//    /**
//     * @return Professionnel[] Returns an array of Professionnel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Professionnel
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
