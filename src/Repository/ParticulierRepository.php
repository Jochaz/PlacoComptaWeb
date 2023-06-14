<?php

namespace App\Repository;

use App\Entity\Particulier;
use App\Model\SearchDataParticulier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Particulier>
 *
 * @method Particulier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Particulier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Particulier[]    findAll()
 * @method Particulier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticulierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Particulier::class);
    }

    public function save(Particulier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Particulier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginationQuery()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom, p.prenom', 'ASC')
            ->andWhere('p.actif = :val')
            ->setParameter('val', true)
            ->getQuery()
        ;
    }

    public function findBySearch(SearchDataParticulier $searchData){
        $data =  $this->createQueryBuilder('p')
            ->andWhere('p.actif = :val')
            ->setParameter('val', true)
            ->addOrderby('p.nom, p.prenom', 'asc');

        if (!empty($searchData->nom)){
            $data = $data 
                ->andwhere ('p.nom LIKE :nom')
                ->setParameter('nom', "%{$searchData->nom}%");
        }
        if (!empty($searchData->prenom)){
            $data = $data 
                ->andwhere ('p.prenom LIKE :prenom')
                ->setParameter('prenom', "%{$searchData->prenom}%");
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


//    /**
//     * @return Particulier[] Returns an array of Particulier objects
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

//    public function findOneBySomeField($value): ?Particulier
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
