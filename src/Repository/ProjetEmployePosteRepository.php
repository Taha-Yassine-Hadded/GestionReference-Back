<?php

namespace App\Repository;

use App\Entity\ProjetEmployePoste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjetEmployePoste>
 *
 * @method ProjetEmployePoste|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjetEmployePoste|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjetEmployePoste[]    findAll()
 * @method ProjetEmployePoste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetEmployePosteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjetEmployePoste::class);
    }

//    /**
//     * @return ProjetEmployePoste[] Returns an array of ProjetEmployePoste objects
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

//    public function findOneBySomeField($value): ?ProjetEmployePoste
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
