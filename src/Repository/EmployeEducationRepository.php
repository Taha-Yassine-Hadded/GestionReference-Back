<?php

namespace App\Repository;

use App\Entity\EmployeEducation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeEducation>
 *
 * @method EmployeEducation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeEducation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeEducation[]    findAll()
 * @method EmployeEducation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeEducationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeEducation::class);
    }

    //    /**
    //     * @return EmployeEducation[] Returns an array of EmployeEducation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EmployeEducation
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
