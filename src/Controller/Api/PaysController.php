<?php

namespace App\Controller\Api;

use App\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PaysController extends AbstractController
{
    #[Route('/api/create/pays', name: 'api_pays_create', methods: ['POST'])]
    #[Security('is_granted("ROLE_USER")')] 
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Créer une nouvelle instance de Pays
        $pays = new Pays();
        $pays->setPaysNom($requestData['paysNom']);

        // Persister l'entité dans la base de données
        $entityManager->persist($pays);
        $entityManager->flush();

        // Retourner une réponse JSON avec un message de succès
        return new JsonResponse(['message' => 'Pays créé avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/get/pays/{id}', name: 'api_pays_show', methods: ['GET'])]
    public function show(Pays $pays): JsonResponse
    {
        $data = [
            'paysId' => $pays->getPaysId(),
            'paysNom' => $pays->getPaysNom(),
            // Ajoutez d'autres attributs du pays que vous souhaitez inclure
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
    #[Route('/api/getAll/pays', name: 'api_get_all_pays', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer tous les pays depuis le repository
        $paysRepository = $entityManager->getRepository(Pays::class);
        $pays = $paysRepository->findAll();

        // Initialiser un tableau pour stocker les données des pays
        $paysData = [];

        // Boucler à travers chaque pays pour extraire les informations nécessaires
        foreach ($pays as $paysItem) {
            $paysData[] = [
                'paysId' => $paysItem->getPaysId(),
                'paysNom' => $paysItem->getPaysNom(),
                // Ajouter d'autres attributs de pays si nécessaire
            ];
        }

        // Retourner les données des pays sous forme de réponse JSON
        return new JsonResponse($paysData, Response::HTTP_OK);
    }

    #[Route('/api/put/pays/{id}', name: 'api_pays_update', methods: ['PUT'])]
    public function update(Request $request, Pays $pays, EntityManagerInterface $entityManager): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $pays->setPaysNom($requestData['paysNom']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Pays mis à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/pays/{id}', name: 'api_pays_delete', methods: ['DELETE'])]
    public function delete(Pays $pays, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($pays);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Pays supprimé avec succès'], Response::HTTP_OK);
    }
}
