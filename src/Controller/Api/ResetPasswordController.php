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

    #[Route('/api/forgot-password', name: 'forgot_password', methods: ['POST'])]
    public function forgotPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
    
        if (!$email) {
            return new JsonResponse(['message' => 'Email manquant'], Response::HTTP_BAD_REQUEST);
        }
    
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
    
        // Obtenir l'URL du frontend à partir des paramètres de configuration
        $frontendUrl = $this->getParameter('app.frontend_url');
    
        // Générer le lien de réinitialisation de mot de passe
        $resetUrl = $frontendUrl . '/confirmPwd/' . $resetToken;
    
        // Envoyer un email de réinitialisation de mot de passe à l'utilisateur
        $email = (new Email())
            ->from('recover.password@xtensus.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de mot de passe')
            ->text('Pour réinitialiser votre mot de passe, cliquez sur ce lien : ' . $resetUrl);
    
        $mailer->send($email);
    
        // Répondre avec un message de succès
        return new JsonResponse(['message' => 'Un email de réinitialisation de mot de passe a été envoyé à votre adresse email']);
    }
    #[Route('/api/update-password', name: 'update_password', methods: ['POST'])]
public function updatePassword(Request $request): Response
{
    $data = json_decode($request->getContent(), true);
    $token = $data['token'] ?? null;
    $newPassword = $data['newPassword'] ?? null;

    if (!$token || !$newPassword) {
        return $this->json(['error' => 'Invalid or missing token or password'], Response::HTTP_BAD_REQUEST);
    }

    $user = $this->userRepository->findOneBy(['resetToken' => $token]);

    if (!$user) {
        return $this->json(['error' => 'Invalid token'], Response::HTTP_BAD_REQUEST);
    }

    // Simple password hashing (not recommended for production)
    $hashedPassword = hash('sha256', $newPassword);
    $user->setPassword($hashedPassword);
    $user->setResetToken(null); // Clear the reset token
    $this->entityManager->flush();

    return $this->json(['message' => 'Password updated successfully']);
}}