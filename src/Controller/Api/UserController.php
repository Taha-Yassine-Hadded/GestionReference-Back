<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use App\Entity\Notification;
use App\Entity\UserNotification;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\UserNotificationRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $userRepository;
    private $passwordHasher;
    private $jwtManager;
    private $authorizationChecker;
    private $security;

    private $notificationRepository;
    

    public function __construct(EntityManagerInterface $entityManager,  NotificationRepository $notificationRepository,UserNotificationRepository $userNotificationRepository,SerializerInterface $serializer, Security $security, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->security = $security;
        $this->notificationRepository = $notificationRepository;
        $this->userNotificationRepository = $userNotificationRepository;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/userCreate', name: 'user_create', methods: ['POST'])]
    public function userCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data["email"];
        $password = $data["password"];
        $role = $data["role"];
        $username = $data["username"];

        // Création d'un nouvel utilisateur
        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles([$role]);
        $user->setUsername($username);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            "status" => true,
            "message" => "L'utilisateur a été créé avec succès !",
        ]);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
    
        // Récupérer les identifiants de l'utilisateur
        $email = $data['email'];
        $password = $data['password'];
    
        // Récupérer l'utilisateur depuis le repository
        $user = $this->userRepository->findOneBy(['email' => $email]);
    
        // Vérifier si l'utilisateur existe
        if (!$user) {
            throw new BadCredentialsException('Invalid email or password');
        }
    
        // Vérifier si le mot de passe est correct
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Invalid email or password');
        }
    
        // Authentification réussie, générer le jeton JWT
        $token = $this->jwtManager->create($user);

 

        // Retourner le jeton JWT et le nom d'utilisateur dans la réponse
        return new JsonResponse(['token' => $token, 'username' => $user->getUsername()]);
    }

    #[Route('api/logout', name: 'logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        // Clear the JWT token from the client-side cookie or local storage
        $response = new JsonResponse(['message' => 'Logged out successfully']);
        $response->headers->clearCookie('JWT'); // Change 'JWT' to the name of your JWT cookie, if applicable

        return $response;
    }

    #[Route('api/changePassword', name: 'changePassword', methods: ['POST'])]
    public function changePassword(Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
   
        $data = json_decode($request->getContent(), true);

        // Assurez-vous que les données nécessaires sont présentes dans la requête
        if (!isset($data['oldPassword']) || !isset($data['newPassword'])) {
            return new JsonResponse(['error' => 'Les données de requête sont incomplètes'], 400);
        }

        // Récupérer l'utilisateur actuel
        $user = $this->getUser();

        // Récupérer l'ancien et le nouveau mot de passe du corps de la requête
        $oldPassword = $data['oldPassword'];
        $newPassword = $data['newPassword'];

        // Vérifier si l'ancien mot de passe est correct
        if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
            return new JsonResponse(['error' => 'Le mot de passe actuel est incorrect'], 400);
        }

        // Encoder et mettre à jour le mot de passe de l'utilisateur
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        // Ici, vous devriez avoir un mécanisme pour sauvegarder le mot de passe mis à jour, par exemple, dans un fichier ou une base de données

        // Authentifier l'utilisateur avec le nouveau mot de passe
        $this->entityManager->flush();

        // Retourner le message de succès
        return new JsonResponse(['message' => 'Mot de passe changé avec succès'], 200);
    }

    #[Route('/forgot-password', name: 'forgot_password', methods: ['POST'])]
    public function forgotPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];

        // Récupérer l'utilisateur à partir de l'email
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Générer un jeton de réinitialisation de mot de passe
        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);

        // Enregistrer le jeton de réinitialisation de mot de passe dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Envoyer un email de réinitialisation de mot de passe à l'utilisateur
        $email = (new Email())
            ->from('recover.password@xtensus.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de mot de passe')
            ->text('Pour réinitialiser votre mot de passe, cliquez sur ce lien : ' . $this->generateUrl('reset_password', ['token' => $resetToken], UrlGeneratorInterface::ABSOLUTE_URL));

        $mailer->send($email);

        // Répondre avec un message de succès
        return new JsonResponse(['message' => 'Un email de réinitialisation de mot de passe a été envoyé à votre adresse email']);
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
