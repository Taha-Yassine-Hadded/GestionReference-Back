<?php

namespace App\Controller\Api;

use App\Entity\AppelOffreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AppelOffreTypeController extends AbstractController
{
    #[Route('/api/create/appeloffre/types', name: 'api_appel_offre_type_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $appelOffreType = new AppelOffreType();
        $appelOffreType->setAppelOffreType($data['appelOffreType']);

        $entityManager->persist($appelOffreType);
        $entityManager->flush();

        return new JsonResponse('Type d\'appel d\'offre créé avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/getAll/appeloffre/types', name: 'api_appel_offre_types', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $appelOffreTypes = $entityManager->getRepository(AppelOffreType::class)->findAll();
        $data = [];

        foreach ($appelOffreTypes as $appelOffreType) {
            $data[] = [
                'appelOffreTypeId' => $appelOffreType->getId(),
                'appelOffreType' => $appelOffreType->getAppelOffreType(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/appeloffre/types/{id}', name: 'api_appel_offre_type_show', methods: ['GET'])]
    public function show(AppelOffreType $appelOffreType): JsonResponse
    {
        $data = [
            'appelOffreTypeId' => $appelOffreType->getId(),
            'appelOffreType' => $appelOffreType->getAppelOffreType(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/put/appeloffre/types/{id}', name: 'api_appel_offre_type_update', methods: ['PUT'])]
    public function update(Request $request, AppelOffreType $appelOffreType, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $appelOffreType->setAppelOffreType($data['appelOffreType']);

        $entityManager->flush();

        return new JsonResponse('Appel d\'offre type mis à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/appeloffre/types/{id}', name: 'api_appel_offre_type_delete', methods: ['DELETE'])]
    public function delete(AppelOffreType $appelOffreType, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($appelOffreType);
        $entityManager->flush();

        return new JsonResponse('Appel d\'offre type supprimé avec succès', Response::HTTP_OK);
    }
}
