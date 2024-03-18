<?php

namespace App\Repository;

use App\Entity\ProjetPreuve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjetPreuve>
 *
 * @method ProjetPreuve|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjetPreuve|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjetPreuve[]    findAll()
 * @method ProjetPreuve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetPreuveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjetPreuve::class);
    }

//    /**
//     * @return ProjetPreuve[] Returns an array of ProjetPreuve objects
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

//    public function findOneBySomeField($value): ?ProjetPreuve
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
