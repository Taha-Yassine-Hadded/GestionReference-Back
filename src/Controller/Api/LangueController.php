<?php

namespace App\Controller\Api;

use App\Entity\Employe;
use App\Entity\Langue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LangueController extends AbstractController
{
    #[Route('/api/create/langue', name: 'api_langue_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $langue = new Langue();
        $langue->setLangueNom($data['langueNom']);

        $entityManager->persist($langue);
        $entityManager->flush();

        return new JsonResponse('Langue créée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/get/langue/{id}', name: 'api_langue_get', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $langue = $entityManager->getRepository(Langue::class)->find($id);

        if (!$langue) {
            return new JsonResponse(['message' => 'Langue non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $langue->getId(),
            'langueNom' => $langue->getLangueNom(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/put/langue/{id}', name: 'api_langue_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $langue = $entityManager->getRepository(Langue::class)->find($id);

        if (!$langue) {
            return new JsonResponse(['message' => 'Langue non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $langue->setLangueNom($data['langueNom']);

        $entityManager->flush();

        return new JsonResponse('Langue mise à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/langue/{id}', name: 'api_langue_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $langue = $entityManager->getRepository(Langue::class)->find($id);

        if (!$langue) {
            return new JsonResponse(['message' => 'Langue non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($langue);
        $entityManager->flush();

        return new JsonResponse('Langue supprimée avec succès', Response::HTTP_OK);
    }

    #[Route('/api/getAll/langues', name: 'api_langue_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $langues = $entityManager->getRepository(Langue::class)->findAll();

        $data = [];
        foreach ($langues as $langue) {
            $data[] = [
                'id' => $langue->getId(),
                'langueNom' => $langue->getLangueNom(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
