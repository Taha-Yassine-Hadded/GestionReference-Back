<?php

namespace App\Controller\Api;
use App\Entity\Client;
use App\Entity\NatureClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ClientController extends AbstractController
{
    #[Route('/api/getAll/clients', name: 'api_client_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $clients = $entityManager->getRepository(Client::class)->findAll();
        $data = [];

        foreach ($clients as $client) {
            $data[] = $this->serializeClient($client);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/client/{id}', name: 'api_client_show', methods: ['GET'])]
    public function show(Client $client): JsonResponse
    {
        return new JsonResponse($this->serializeClient($client), Response::HTTP_OK);
    }

    #[Route('/api/create/clients', name: 'api_client_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $client = new Client();
        $client->setPersonneContact($data['personneContact']);
        $client->setClientRaisonSociale($data['clientRaisonSociale']);
        $client->setClientAdresse($data['clientAdresse']);
        $client->setClientTelephone($data['clientTelephone']);
        $client->setClientEmail($data['clientEmail']);

        // Récupérer la nature du client associée
        $natureClient = $entityManager->getRepository(NatureClient::class)->find($data['natureClientId']);
        if (!$natureClient) {
            return new JsonResponse(['message' => 'Nature du client introuvable'], Response::HTTP_NOT_FOUND);
        }
        $client->setNatureClient($natureClient);

        $entityManager->persist($client);
        $entityManager->flush();

        return new JsonResponse('Client créé avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/put/client/{id}', name: 'api_client_update', methods: ['PUT'])]
    public function update(Request $request, Client $client, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $client->setPersonneContact($data['personneContact']);
        $client->setClientRaisonSociale($data['clientRaisonSociale']);
        $client->setClientAdresse($data['clientAdresse']);
        $client->setClientTelephone($data['clientTelephone']);
        $client->setClientEmail($data['clientEmail']);

        // Mise à jour de la nature du client associée
        if (isset($data['natureClientId'])) {
            $natureClient = $entityManager->getRepository(NatureClient::class)->find($data['natureClientId']);
            if (!$natureClient) {
                return new JsonResponse(['message' => 'Nature du client introuvable'], Response::HTTP_NOT_FOUND);
            }
            $client->setNatureClient($natureClient);
        }

        $entityManager->flush();

        return new JsonResponse('Client mis à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/client/{id}', name: 'api_client_delete', methods: ['DELETE'])]
    public function delete(Client $client, EntityManagerInterface $entityManager): JsonResponse
    {
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
            'clientId' => $client->getClientId(),
            'personneContact' => $client->getPersonneContact(),
            'clientRaisonSociale' => $client->getClientRaisonSociale(),
            'clientAdresse' => $client->getClientAdresse(),
            'clientTelephone' => $client->getClientTelephone(),
            'clientEmail' => $client->getClientEmail(),
            'natureClientId' => $client->getNatureClient() ? $client->getNatureClient()->getNatureClientId() : null,
            // Ajoutez d'autres attributs de l'entité que vous souhaitez inclure dans la réponse JSON
        ];
    }
}
