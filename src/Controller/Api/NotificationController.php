<?php

namespace App\Controller\Api;

use App\Entity\AppelOffre;
use App\Entity\AppelOffreType;
use App\Entity\MoyenLivraison;
use App\Entity\OrganismeDemandeur;
use App\Repository\AppelOffreRepository;
use App\Entity\Notification;
use App\Entity\UserNotification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use DoctrineTrait;

class NotificationController extends AbstractController
{
    private $entityManager;
 

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/notifications', name: 'api_notification', methods: ['GET'])]
    public function checkAchèvementDate(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        
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

    #[Route('/api/notifications/unread-count', name: 'api_notifications_unread_count', methods: ['GET'])]
    public function getUnreadNotificationCount(NotificationRepository $notificationRepository, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        // Récupérer le nombre de notifications non lues
        $unreadNotificationCount = $notificationRepository->countUnreadNotifications();

        // Retourner le nombre de notifications non lues au format JSON
        return new JsonResponse(['unread_notification_count' => $unreadNotificationCount]);
    }

    #[Route('/api/notifications/all', name: 'api_notifications_all', methods: ['GET'])]
    public function getNotifications(NotificationRepository $notificationRepository, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        // Récupérer toutes les notifications avec les données de l'appel d'offre associées
        $notifications = $notificationRepository->findAllWithAppelOffre();

        // Convertir les notifications en un tableau associatif pour le retour JSON
        $notificationsArray = [];
        foreach ($notifications as $notification) {
            $notificationsArray[] = [
                'id' => $notification->getId(),
                'message' => $notification->getMessage(),
                'dateCreation' => $notification->getDateCreation()->format('Y-m-d H:i:s'), // Formatage de la date
                'appelOffre' => [
                    'id' => $notification->getAppelOffre()->getId(),
                    'devis' => $notification->getAppelOffre()->getAppelOffreDevis(),
                    'objet' => $notification->getAppelOffre()->getAppelOffreObjet(),
                    'dateRemise' => $notification->getAppelOffre()->getAppelOffreDateRemise() ? $notification->getAppelOffre()->getAppelOffreDateRemise()->format('Y-m-d') : null,
                    // Ajoutez d'autres propriétés de l'appel d'offre si nécessaire
                ],
                // Ajoutez d'autres propriétés de notification si nécessaire
            ];
        }

        // Retourner les notifications au format JSON
        return new JsonResponse($notificationsArray);
    }
    


    #[Route('/api/sendNotification/{id}', name: 'sendNotification', methods: ['GET'])]
    public function sendNotification(int $id, TokenStorageInterface $tokenStorage, Request $request): void
    {
        $this->checkToken($tokenStorage);
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
    
        // Récupérer l'objet AppelOffre en fonction de son ID
        $appelOffre = $entityManager->getRepository(AppelOffre::class)->find($id);
    
        if (!$appelOffre) {
            throw $this->createNotFoundException('Aucun AppelOffre trouvé pour cet ID.');
        }
    
        // Vérifier si une notification identique non lue existe déjà pour cet utilisateur
        $existingNotification = $entityManager->getRepository(Notification::class)->findOneBy([
            'appelOffre' => $appelOffre,
            'user' => $user,
            'isRead' => false,
        ]);
    
        if (!$existingNotification) {
            // Créer une nouvelle notification
            $notification = new Notification();
            $notification->setMessage("La date d'achèvement de l'appel d'offre est proche.");
            $notification->setDateCreation(new \DateTime());
            $notification->setAppelOffre($appelOffre);
            $notification->setUser($user);
            $notification->setIsRead(false); // Initialiser comme non lue
    
            // Enregistrer la notification en base de données
            $entityManager->persist($notification);
            $entityManager->flush();
    
            // Créer une nouvelle instance de UserNotification
         // Créer une nouvelle instance de UserNotification
         $userNotification = new UserNotification();
         $userNotification->setUser($user);
         $userNotification->setNotification($notification);
         $userNotification->setIsRead(false); // Initialiser comme non lue
 
         // Enregistrer la relation dans la base de données
         $entityManager->persist($userNotification);
         $entityManager->flush();
        }
    }
    public function checkToken(TokenStorageInterface $tokenStorage): void
    {
        // Récupérer le token d'authentification de Symfony
        $token = $tokenStorage->getToken();

        // Vérifier si le token d'authentification est présent et est de type TokenInterface
        if (!$token instanceof TokenInterface) {
            throw new AccessDeniedHttpException('Token d\'authentification manquant ou invalide');
        }

}
}
