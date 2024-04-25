<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Projet;
use App\Entity\NatureClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ClientController extends AbstractController
{
    #[Route('/api/getAll/clients', name: 'api_client_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $clients = $entityManager->getRepository(Client::class)->findAll();
        $data = [];

        foreach ($clients as $client) {
            $data[] = $this->serializeClientNom($client);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/client/{id}', name: 'api_client_show', methods: ['GET'])]
    public function show(Client $client, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = $this->serializeClient($client);

        return new JsonResponse($data, Response::HTTP_OK);
    }
    #[Route('/api/getOne/client/{id}', name: 'api_client_Nom', methods: ['GET'])]
    public function getByNom(Client $client, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = $this->serializeClientNom($client);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/create/clients', name: 'api_client_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $client = new Client();
        $client->setPersonneContact($data['personneContact']);
        $client->setClientRaisonSociale($data['clientRaisonSociale']);
        $client->setClientAdresse($data['clientAdresse']);
        $client->setClientTelephone($data['clientTelephone']);
        $client->setClientEmail($data['clientEmail']);

        // Récupérer la nature du client associée
        $natureClientId = $data['natureClientId'];
        $natureClient = $entityManager->getRepository(NatureClient::class)->find($natureClientId);
        if (!$natureClient) {
            return new JsonResponse(['message' => 'Nature du client introuvable'], Response::HTTP_NOT_FOUND);
        }
        $client->setNatureClient($natureClient);

        $entityManager->persist($client);
        $entityManager->flush();

        $responseData = $this->serializeClient($client);
        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }
    #[Route('/api/update/client/{id}', name: 'api_client_update', methods: ['PUT'])]
    public function update(Request $request, Client $client, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);
    
        // Mettre à jour les propriétés du client
        $client->setPersonneContact($data['personneContact']);
        $client->setClientRaisonSociale($data['clientRaisonSociale']);
        $client->setClientAdresse($data['clientAdresse']);
        $client->setClientTelephone($data['clientTelephone']);
        $client->setClientEmail($data['clientEmail']);
    
        // Récupérer la nature du client associée et la mettre à jour si elle a changé
        $natureClientId = $data['natureClientId'];
        $natureClient = $entityManager->getRepository(NatureClient::class)->find($natureClientId);
        if (!$natureClient) {
            return new JsonResponse(['message' => 'Nature du client introuvable'], Response::HTTP_NOT_FOUND);
        }
        $client->setNatureClient($natureClient);
    
        $entityManager->flush();
    
        $responseData = $this->serializeClient($client);
        return new JsonResponse($responseData, Response::HTTP_OK);
    }
    
    #[Route('/api/delete/client/{id}', name: 'api_client_delete', methods: ['DELETE'])]
    public function deleteClient(Client $client, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        
        // Récupérer tous les projets qui ont ce client
        $projets = $entityManager->getRepository(Projet::class)->findBy(['client' => $client]);

        // Mettre à jour les références à null dans tous les projets liés
        foreach ($projets as $projet) {
            $projet->setClient(null);
            $entityManager->persist($projet);
        }
        $entityManager->flush();

        // Supprimer le client
        $entityManager->remove($client);
        $entityManager->flush();

        return new JsonResponse('Client supprimé avec succès', Response::HTTP_OK);
    }
    /**
     * Serialize Client entity to array.
     */
    private function serializeClient(Client $client): array
    {
        return [
            'clientId' => $client->getId(),
            'personneContact' => $client->getPersonneContact(),
            'clientRaisonSociale' => $client->getClientRaisonSociale(),
            'clientAdresse' => $client->getClientAdresse(),
            'clientTelephone' => $client->getClientTelephone(),
            'clientEmail' => $client->getClientEmail(),
            'natureClientId' => $client->getNatureClient() ? $client->getNatureClient()->getId() : null
            // Ajoutez d'autres attributs de l'entité que vous souhaitez inclure dans la réponse JSON
        ];
    }
     /**
     * Serialize Client entity to array.
     */
    private function serializeClientNom(Client $client): array
    {
        return [
            'clientId' => $client->getId(),
            'personneContact' => $client->getPersonneContact(),
            'clientRaisonSociale' => $client->getClientRaisonSociale(),
            'clientAdresse' => $client->getClientAdresse(),
            'clientTelephone' => $client->getClientTelephone(),
            'clientEmail' => $client->getClientEmail(),
            'natureClientId' => $client->getNatureClient() ? $client->getNatureClient()->getNatureClient() : null
            // Ajoutez d'autres attributs de l'entité que vous souhaitez inclure dans la réponse JSON
        ];
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
