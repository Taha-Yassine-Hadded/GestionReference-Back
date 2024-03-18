<?php

namespace App\Repository;

use App\Entity\MoyenLivraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MoyenLivraison>
 *
 * @method MoyenLivraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoyenLivraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoyenLivraison[]    findAll()
 * @method MoyenLivraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoyenLivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoyenLivraison::class);
    }

    //    /**
    //     * @return MoyenLivraison[] Returns an array of MoyenLivraison objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MoyenLivraison
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
