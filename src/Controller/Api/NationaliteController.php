<?php

namespace App\Controller\Api;

use App\Entity\Nationalite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class NationaliteController extends AbstractController
{
    #[Route('/api/create/nationalite', name: 'api_nationalite_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nationalite = new Nationalite();
        $nationalite->setNationaliteLibelle($data['nationaliteLibelle']);

        $entityManager->persist($nationalite);
        $entityManager->flush();

        return new JsonResponse('Nationalité créée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/get/nationalite/{id}', name: 'api_nationalite_show', methods: ['GET'])]
    public function show(Nationalite $nationalite): JsonResponse
    {
        $data = [
            'id' => $nationalite->getId(),
            'nationaliteLibelle' => $nationalite->getNationaliteLibelle(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/getAll/nationalites', name: 'api_nationalite_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $nationalites = $entityManager->getRepository(Nationalite::class)->findAll();
        $data = [];

        foreach ($nationalites as $nationalite) {
            $data[] = [
                'id' => $nationalite->getId(),
                'nationaliteLibelle' => $nationalite->getNationaliteLibelle(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
   

    #[Route('/api/put/nationalite/{id}', name: 'api_nationalite_update', methods: ['PUT'])]
    public function update(Request $request, Nationalite $nationalite, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nationalite->setNationaliteLibelle($data['nationaliteLibelle']);

        $entityManager->flush();

        return new JsonResponse('Nationalité mise à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/nationalite/{id}', name: 'api_nationalite_delete', methods: ['DELETE'])]
    public function delete(Nationalite $nationalite, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($nationalite);
        $entityManager->flush();

        return new JsonResponse('Nationalité supprimée avec succès', Response::HTTP_OK);
    }
}