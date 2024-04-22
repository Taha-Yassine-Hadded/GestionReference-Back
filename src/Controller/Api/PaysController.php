<?php

namespace App\Controller\Api;

use App\Entity\Pays;
use App\Entity\Lieu;
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

class PaysController extends AbstractController
{
    #[Route('/api/create/pays', name: 'api_pays_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
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
    public function show(Pays $pays, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = [
            'paysId' => $pays->getId(),
            'paysNom' => $pays->getPaysNom(),
            // Ajoutez d'autres attributs du pays que vous souhaitez inclure
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
    #[Route('/api/getAll/pays', name: 'api_get_all_pays', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        // Récupérer tous les pays depuis le repository
        $paysRepository = $entityManager->getRepository(Pays::class);
        $pays = $paysRepository->findAll();

        // Initialiser un tableau pour stocker les données des pays
        $paysData = [];

        // Boucler à travers chaque pays pour extraire les informations nécessaires
        foreach ($pays as $paysItem) {
            $paysData[] = [
                'paysId' => $paysItem->getId(),
                'paysNom' => $paysItem->getPaysNom(),
                // Ajouter d'autres attributs de pays si nécessaire
            ];
        }

        // Retourner les données des pays sous forme de réponse JSON
        return new JsonResponse($paysData, Response::HTTP_OK);
    }
 
    #[Route('/api/put/pays/{id}', name: 'api_pays_update', methods: ['PUT'])]
    public function update(Request $request, Pays $pays, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $requestData = json_decode($request->getContent(), true);

        $pays->setPaysNom($requestData['paysNom']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Pays mis à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/pays/{id}', name: 'api_pays_delete', methods: ['DELETE'])]
    public function deletePays(Pays $pays, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        
        // Récupérer tous les lieux qui ont ce pays
        $lieux = $entityManager->getRepository(Lieu::class)->findBy(['pays' => $pays]);

        // Mettre à jour les références à null dans tous les lieux liés
        foreach ($lieux as $lieu) {
            $lieu->setPays(null);
            $entityManager->persist($lieu);
        }
        $entityManager->flush();

        // Supprimer le pays
        $entityManager->remove($pays);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Pays supprimé avec succès'], Response::HTTP_OK);
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
