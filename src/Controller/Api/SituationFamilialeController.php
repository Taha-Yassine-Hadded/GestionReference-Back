<?php

namespace App\Controller\Api;

use App\Entity\SituationFamiliale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class SituationFamilialeController extends AbstractController
{
    #[Route('/api/getAll/situations-familiales', name: 'api_situation_familiale_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
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
    public function show(SituationFamiliale $situationFamiliale): JsonResponse
    {
        $data = [
            'id' => $situationFamiliale->getId(),
            'situationFamiliale' => $situationFamiliale->getSituationFamiliale(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/create/situations-familiales', name: 'api_situation_familiale_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $situationFamiliale = new SituationFamiliale();
        $situationFamiliale->setSituationFamiliale($data['situationFamiliale']);

        $entityManager->persist($situationFamiliale);
        $entityManager->flush();

        return new JsonResponse('Situation familiale créée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/put/situations-familiales/{id}', name: 'api_situation_familiale_update', methods: ['PUT'])]
    public function update(Request $request, SituationFamiliale $situationFamiliale, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $situationFamiliale->setSituationFamiliale($data['situationFamiliale']);

        $entityManager->flush();

        return new JsonResponse('Situation familiale mise à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/situations-familiales/{id}', name: 'api_situation_familiale_delete', methods: ['DELETE'])]
    public function delete(SituationFamiliale $situationFamiliale, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($situationFamiliale);
        $entityManager->flush();

        return new JsonResponse('Situation familiale supprimée avec succès', Response::HTTP_OK);
    }
}

