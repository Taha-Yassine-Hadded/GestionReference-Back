<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function countUnreadNotifications(): int
    {
        return $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->andWhere('n.isread= :isread')
            ->setParameter('isread', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
    // Ajoutez la méthode findAllWithAppelOffre pour récupérer toutes les notifications avec les données de l'appel d'offre associées
    public function findAllWithAppelOffre()
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.appelOffre', 'a') // Joignez l'entité AppelOffre avec l'alias 'a'
            ->addSelect('a') // Sélectionnez l'entité AppelOffre pour l'inclure dans les résultats
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Notification[] Returns an array of Notification objects
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

//    public function findOneBySomeField($value): ?Notification
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
