<?php

namespace App\Repository;

use App\Entity\NatureClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NatureClient>
 *
 * @method NatureClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method NatureClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method NatureClient[]    findAll()
 * @method NatureClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NatureClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NatureClient::class);
    }

//    /**
//     * @return NatureClient[] Returns an array of NatureClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NatureClient
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
