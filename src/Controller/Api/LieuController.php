<?php

namespace App\Controller\Api;

use App\Entity\Lieu;
use App\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class LieuController extends AbstractController
{
    #[Route('/api/create/lieu', name: 'api_lieu_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Créer une nouvelle instance de Lieu
        $lieu = new Lieu();
        $lieu->setLieuNom($requestData['lieuNom']);

        // Récupérer l'objet Pays en fonction de l'ID fourni dans la requête
        $pays = $entityManager->getRepository(Pays::class)->find($requestData['paysId']);

        // Vérifier si le pays existe
        if (!$pays) {
            return new JsonResponse(['message' => 'Pays non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Affecter le pays au lieu
        $lieu->setPays($pays);

        // Persister l'entité dans la base de données
        $entityManager->persist($lieu);
        $entityManager->flush();

        // Retourner une réponse JSON avec un message de succès
        return new JsonResponse(['message' => 'Lieu créé avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/get/lieux/{id}', name: 'api_lieu_show', methods: ['GET'])]
    public function show(Lieu $lieu): JsonResponse
    {
        $data = [
            'lieuId' => $lieu->getLieuId(),
            'lieuNom' => $lieu->getLieuNom(),
            'pays' => [
                'paysId' => $lieu->getPays()->getPaysId(),
                'paysNom' => $lieu->getPays()->getPaysNom(),
                // Ajoutez d'autres attributs du pays que vous souhaitez inclure
            ],
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/put/lieu/{id}', name: 'api_lieu_update', methods: ['PUT'])]
    public function update(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $lieu->setLieuNom($requestData['lieuNom']);

        // Récupérer l'objet Pays en fonction de l'ID fourni dans la requête
        $pays = $entityManager->getRepository(Pays::class)->find($requestData['paysId']);

        // Vérifier si le pays existe
        if (!$pays) {
            return new JsonResponse(['message' => 'Pays non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Affecter le pays au lieu
        $lieu->setPays($pays);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Lieu mis à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/delete/lieux/{id}', name: 'api_lieu_delete', methods: ['DELETE'])]
    public function delete(Lieu $lieu, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($lieu);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Lieu supprimé avec succès'], Response::HTTP_OK);
    }
}