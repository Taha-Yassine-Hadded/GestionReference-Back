<?php

namespace App\Repository;

use App\Entity\AppelOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppelOffre>
 *
 * @method AppelOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppelOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppelOffre[]    findAll()
 * @method AppelOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppelOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppelOffre::class);
    }

    public function countAppelsOffresByPays(): array
    {
        return $this->createQueryBuilder('ao')
            ->select('COUNT(ao.id) AS total, p.id AS paysId')
            ->join('ao.pays', 'p')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }
    public function countAppelsOffresParticipationByPays(): array
    {
        return $this->createQueryBuilder('ao')
            ->select('COUNT(ao.id) AS total, p.id AS paysId')
            ->join('ao.pays', 'p')
            ->where('ao.appelOffreParticipation = :participation')
            ->setParameter('participation', 1) // Si 1 représente la participation
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }
    public function countParticipations()
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(CASE WHEN a.appelOffreParticipation = 1 THEN 1 ELSE 0 END) AS oui', 'SUM(CASE WHEN a.appelOffreParticipation = 0 THEN 1 ELSE 0 END) AS non', 'COUNT(a.id) AS total')
            ->getQuery()
            ->getSingleResult();
    }
//    /**
//     * @return AppelOffre[] Returns an array of AppelOffre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AppelOffre
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
