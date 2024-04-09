<?php

namespace App\Controller\Api;

use App\Entity\SituationFamiliale;
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

class SituationFamilialeController extends AbstractController
{
    #[Route('/api/getAll/situations-familiales', name: 'api_situation_familiale_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $situationsFamiliales = $entityManager->getRepository(SituationFamiliale::class)->findAll();
        $data = [];

        foreach ($situationsFamiliales as $situationFamiliale) {
            $data[] = [
                'id' => $situationFamiliale->getId(),
                'situationFamiliale' => $situationFamiliale->getSituationFamiliale(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/situations-familiales/{id}', name: 'api_situation_familiale_show', methods: ['GET'])]
    public function show(SituationFamiliale $situationFamiliale, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = [
            'id' => $situationFamiliale->getId(),
            'situationFamiliale' => $situationFamiliale->getSituationFamiliale(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/create/situations-familiales', name: 'api_situation_familiale_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $situationFamiliale = new SituationFamiliale();
        $situationFamiliale->setSituationFamiliale($data['situationFamiliale']);

        $entityManager->persist($situationFamiliale);
        $entityManager->flush();

        return new JsonResponse('Situation familiale créée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/put/situations-familiales/{id}', name: 'api_situation_familiale_update', methods: ['PUT'])]
    public function update(Request $request, SituationFamiliale $situationFamiliale, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $situationFamiliale->setSituationFamiliale($data['situationFamiliale']);

        $entityManager->flush();

        return new JsonResponse('Situation familiale mise à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/situations-familiales/{id}', name: 'api_situation_familiale_delete', methods: ['DELETE'])]
    public function delete(SituationFamiliale $situationFamiliale, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $entityManager->remove($situationFamiliale);
        $entityManager->flush();

        return new JsonResponse('Situation familiale supprimée avec succès', Response::HTTP_OK);
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

