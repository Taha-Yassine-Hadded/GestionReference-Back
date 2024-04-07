<?php

namespace App\Controller\Api;

use App\Entity\MoyenLivraison;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MoyenLivraisonController extends AbstractController
{
    #[Route('/api/create/moyen-livraisons', name: 'api_moyen_livraison_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $moyenLivraison = new MoyenLivraison();
        $moyenLivraison->setMoyenLivraison($data['moyenLivraison']);

        $entityManager->persist($moyenLivraison);
        $entityManager->flush();

        return new JsonResponse('Moyen de livraison créé avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/getAll/moyen-livraisons', name: 'api_moyen_livraison_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $moyenLivraisons = $entityManager->getRepository(MoyenLivraison::class)->findAll();
        $data = [];

        foreach ($moyenLivraisons as $moyenLivraison) {
            $data[] = [
                'moyenLivraisonId' => $moyenLivraison->getId(),
                'moyenLivraison' => $moyenLivraison->getMoyenLivraison(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/moyen-livraisons/{id}', name: 'api_moyen_livraison_get', methods: ['GET'])]
    public function getOne(MoyenLivraison $moyenLivraison): JsonResponse
    {
        $data = [
            'moyenLivraisonId' => $moyenLivraison->getId(),
            'moyenLivraison' => $moyenLivraison->getMoyenLivraison(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/put/moyen-livraisons/{id}', name: 'api_moyen_livraison_update', methods: ['PUT'])]
    public function update(Request $request, MoyenLivraison $moyenLivraison, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $moyenLivraison->setMoyenLivraison($data['moyenLivraison']);

        $entityManager->flush();

        return new JsonResponse('Moyen de livraison mis à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/moyen-livraisons/{id}', name: 'api_moyen_livraison_delete', methods: ['DELETE'])]
    public function delete(MoyenLivraison $moyenLivraison, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($moyenLivraison);
        $entityManager->flush();

        return new JsonResponse('Moyen de livraison supprimé avec succès', Response::HTTP_OK);
    }
}
