<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use  Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



class UserController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $userRepository;
    private $passwordHasher;
    private $jwtManager;
    private $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
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

        // Retourner le jeton JWT dans la réponse
        return new JsonResponse(['token' => $token]);
    }

    #[Route('/api/getUsers', name: 'get_users', methods: ['GET'])]
    public function getUsers(Request $request, AuthorizationCheckerInterface $authorizationChecker): JsonResponse
    {

        // Mettez votre logique pour récupérer les utilisateurs ici
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        // Convertir les utilisateurs en un tableau associatif
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
        }

        // Retourner la réponse JSON avec tous les utilisateurs
        return new JsonResponse($usersArray);
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
            ->from('souha.boushih@gmail.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de mot de passe')
            ->text('Pour réinitialiser votre mot de passe, cliquez sur ce lien : ' . $this->generateUrl('reset_password', ['token' => $resetToken], UrlGeneratorInterface::ABSOLUTE_URL));

        $mailer->send($email);

        // Répondre avec un message de succès
        return new JsonResponse(['message' => 'Un email de réinitialisation de mot de passe a été envoyé à votre adresse email']);
    }
}
