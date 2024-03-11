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

class UserController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $userRepository;
    private $passwordHasher;
    private $jwtManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
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
}
