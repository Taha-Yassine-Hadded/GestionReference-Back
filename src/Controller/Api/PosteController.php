<?php

namespace App\Controller\Api;

use App\Entity\Employe;
use App\Entity\Poste;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PosteController extends AbstractController
{
    #[Route('/api/postes', name: 'api_poste_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $poste = new Poste();
        $poste->setPosteNom($data['posteNom']);

        $entityManager->persist($poste);
        $entityManager->flush();

        return new JsonResponse('Poste créé avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/postes/{id}', name: 'api_poste_get', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $poste = $entityManager->getRepository(Poste::class)->find($id);

        if (!$poste) {
            return new JsonResponse(['message' => 'Poste non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $poste->getId(),
            'posteNom' => $poste->getPosteNom(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/postes/{id}', name: 'api_poste_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $poste = $entityManager->getRepository(Poste::class)->find($id);

        if (!$poste) {
            return new JsonResponse(['message' => 'Poste non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $poste->setPosteNom($data['posteNom']);

        $entityManager->flush();

        return new JsonResponse('Poste mis à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/postes/{id}', name: 'api_poste_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $poste = $entityManager->getRepository(Poste::class)->find($id);

        if (!$poste) {
            return new JsonResponse(['message' => 'Poste non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($poste);
        $entityManager->flush();

        return new JsonResponse('Poste supprimé avec succès', Response::HTTP_OK);
    }

    #[Route('/api/postes', name: 'api_poste_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $postes = $entityManager->getRepository(Poste::class)->findAll();

        $data = [];
        foreach ($postes as $poste) {
            $data[] = [
                'id' => $poste->getId(),
                'posteNom' => $poste->getPosteNom(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
