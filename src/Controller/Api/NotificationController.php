<?php

namespace App\Controller\Api;;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AppelOffre;
use App\Repository\NotificationRepository; 
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class NotificationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/api/notifications', name: 'api_notification', methods: ['GET'])]
    public function checkAchèvementDate()
{
    // Supprimer toutes les anciennes notifications
    $this->entityManager->getRepository(Notification::class)->createQueryBuilder('n')->delete()->getQuery()->execute();

    // Récupérer la date actuelle
    $dateActuelle = new \DateTime();

    // Récupérer tous les AppelOffres
    $appelOffres = $this->entityManager->getRepository(AppelOffre::class)->findAll();

    // Initialiser un tableau pour stocker les IDs des AppelOffres affectés
    $affectedAppelOffresIds = [];

    foreach ($appelOffres as $appelOffre) {
        // Vérifier si la date d'achèvement est dans les 10 jours à partir de la date actuelle
        $limiteNotification = new \DateTime('+10 days');
        if ($appelOffre->getAppelOffreDateRemise() <= $limiteNotification && $appelOffre->getAppelOffreDateRemise() > $dateActuelle) {
            // Créer une nouvelle notification
            $notification = new Notification();
            $notification->setMessage("La date d'achèvement de l'appel d'offre est proche.");
            $notification->setDateCreation(new \DateTime()); // Définir la date de création
            $notification->setAppelOffre($appelOffre);

            // Enregistrer la notification en base de données
            $this->entityManager->persist($notification);

            // Ajouter l'ID de l'AppelOffre à la liste des IDs affectés
            $affectedAppelOffresIds[] = $appelOffre->getId();
        }
    }

    // Enregistrer toutes les notifications créées
    $this->entityManager->flush();
    
    // Retourner une réponse JSON avec les IDs des AppelOffres affectés
    return $this->json([
        'message' => 'Notifications générées avec succès !',
        'affected_appel_offres_ids' => $affectedAppelOffresIds,
    ]);
}
#[Route('/api/notifications/unread-count', name: 'api_notifications_unread_count', methods: ['GET'])] // Renommez la route pour le comptage des notifications non lues
public function getUnreadNotificationCount(NotificationRepository $notificationRepository): JsonResponse
{
    // Récupérer l'utilisateur connecté (vous devrez peut-être ajuster cela selon votre système d'authentification)
    $user = $this->getUser();

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        // Retourner une réponse d'erreur si l'utilisateur n'est pas connecté
        return new JsonResponse(['error' => 'User not authenticated'], 401);
    }

    // Récupérer le nombre de notifications non lues pour l'utilisateur connecté
    $unreadNotificationCount = $notificationRepository->countUnreadNotificationsForUser($user);

    // Retourner le nombre de notifications non lues au format JSON
    return new JsonResponse(['unread_notification_count' => $unreadNotificationCount]);
}
}