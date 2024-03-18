<?php

namespace App\Repository;

use App\Entity\EmployeLangue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeLangue>
 *
 * @method EmployeLangue|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeLangue|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeLangue[]    findAll()
 * @method EmployeLangue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeLangueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeLangue::class);
    }

//    /**
//     * @return EmployeLangue[] Returns an array of EmployeLangue objects
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

//    public function findOneBySomeField($value): ?EmployeLangue
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
