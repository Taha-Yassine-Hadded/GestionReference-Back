<?php

namespace App\Repository;

use App\Entity\OrganismeDemandeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrganismeDemandeur>
 *
 * @method OrganismeDemandeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganismeDemandeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganismeDemandeur[]    findAll()
 * @method OrganismeDemandeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganismeDemandeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganismeDemandeur::class);
    }

    //    /**
    //     * @return OrganismeDemandeur[] Returns an array of OrganismeDemandeur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OrganismeDemandeur
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
