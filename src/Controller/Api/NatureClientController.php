<?php

namespace App\Controller\Api;

use App\Entity\NatureClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class NatureClientController extends AbstractController
{
    
    #[Route('/api/nature-clients', name: 'api_nature_client_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $natureClient = new NatureClient();
        $natureClient->setNatureClient($data['natureClient']); // Assuming 'natureClient' is the field for the nature client

        $entityManager->persist($natureClient);
        $entityManager->flush();

        return new JsonResponse('Nature client créée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/nature-clients', name: 'api_nature_client_get_all', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $natureClients = $entityManager->getRepository(NatureClient::class)->findAll();
        $data = [];

        foreach ($natureClients as $natureClient) {
            $data[] = [
                'id' => $natureClient->getId(),
                'natureClient' => $natureClient->getNatureClient(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/nature-clients/{id}', name: 'api_nature_client_get', methods: ['GET'])]
    public function show(NatureClient $natureClient, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = [
            'id' => $natureClient->getId(),
            'natureClient' => $natureClient->getNatureClient(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/nature-clients/{id}', name: 'api_nature_client_update', methods: ['PUT'])]
    public function update(Request $request, NatureClient $natureClient, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $natureClient->setNatureClient($data['natureClient']); // Assuming 'natureClient' is the field for the nature client

        $entityManager->flush();

        return new JsonResponse('Nature client mise à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/nature-clients/{id}', name: 'api_nature_client_delete', methods: ['DELETE'])]
    public function delete(NatureClient $natureClient, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $entityManager->remove($natureClient);
        $entityManager->flush();

        return new JsonResponse('Nature client supprimée avec succès', Response::HTTP_OK);
    
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