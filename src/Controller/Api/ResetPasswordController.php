<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    private $userRepository;
    private $passwordHasher;
    private $entityManager;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    #[Route('/reset-password/{token}', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, string $token): Response
    {
        // Récupérer l'utilisateur associé au token de réinitialisation
        $user = $this->userRepository->findOneBy(['resetToken' => $token]);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return new JsonResponse(['message' => 'Token invalide'], Response::HTTP_BAD_REQUEST);
        }

        // Extraire les données du corps de la requête
        $data = json_decode($request->getContent(), true);
        $newPassword = $data['new_password'];

        // Hasher le nouveau mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);

        // Mettre à jour le mot de passe de l'utilisateur
        $user->setPassword($hashedPassword);
        $user->setResetToken(null); // Effacer le token de réinitialisation

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Mot de passe réinitialisé avec succès']);
    }
}
