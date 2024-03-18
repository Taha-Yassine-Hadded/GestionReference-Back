<?php

namespace App\Controller\Api;

use App\Entity\NatureClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NatureClientController extends AbstractController
{
    #[Route('/api/nature-clients', name: 'api_nature_client_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $natureClient = new NatureClient();
        $natureClient->setNatureClient($data['natureClient']); // Assuming 'natureClient' is the field for the nature client

        $entityManager->persist($natureClient);
        $entityManager->flush();

        return new JsonResponse('Nature client créée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/nature-clients', name: 'api_nature_client_get_all', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $natureClients = $entityManager->getRepository(NatureClient::class)->findAll();
        $data = [];

        foreach ($natureClients as $natureClient) {
            $data[] = [
                'id' => $natureClient->getNatureClientId(),
                'natureClient' => $natureClient->getNatureClient(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/nature-clients/{id}', name: 'api_nature_client_get', methods: ['GET'])]
    public function show(NatureClient $natureClient): JsonResponse
    {
        $data = [
            'id' => $natureClient->getNatureClientId(),
            'natureClient' => $natureClient->getNatureClient(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/nature-clients/{id}', name: 'api_nature_client_update', methods: ['PUT'])]
    public function update(Request $request, NatureClient $natureClient, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $natureClient->setNatureClient($data['natureClient']); // Assuming 'natureClient' is the field for the nature client

        $entityManager->flush();

        return new JsonResponse('Nature client mise à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/nature-clients/{id}', name: 'api_nature_client_delete', methods: ['DELETE'])]
    public function delete(NatureClient $natureClient, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($natureClient);
        $entityManager->flush();

        return new JsonResponse('Nature client supprimée avec succès', Response::HTTP_OK);
    }
}
