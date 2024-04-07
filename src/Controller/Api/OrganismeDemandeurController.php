<?php

namespace App\Controller\Api;

use App\Entity\OrganismeDemandeur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class OrganismeDemandeurController extends AbstractController
{
    #[Route('/api/create/organisme-demandeurs', name: 'api_organisme_demandeur_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $organismeDemandeur = new OrganismeDemandeur();
        $organismeDemandeur->setOrganismeDemandeurLibelle($data['organismeDemandeurLibelle']);

        $entityManager->persist($organismeDemandeur);
        $entityManager->flush();

        return new JsonResponse('Organisme demandeur créé avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/getAll/organisme-demandeurs', name: 'api_organisme_demandeur_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $organismeDemandeurs = $entityManager->getRepository(OrganismeDemandeur::class)->findAll();
        $data = [];

        foreach ($organismeDemandeurs as $organismeDemandeur) {
            $data[] = [
                'organismeDemandeurId' => $organismeDemandeur->getId(),
                'organismeDemandeurLibelle' => $organismeDemandeur->getOrganismeDemandeurLibelle(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/organisme-demandeurs/{id}', name: 'api_organisme_demandeur_get', methods: ['GET'])]
    public function getOne(OrganismeDemandeur $organismeDemandeur): JsonResponse
    {
        $data = [
            'organismeDemandeurId' => $organismeDemandeur->getId(),
            'organismeDemandeurLibelle' => $organismeDemandeur->getOrganismeDemandeurLibelle(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/put/organisme-demandeurs/{id}', name: 'api_organisme_demandeur_update', methods: ['PUT'])]
    public function update(Request $request, OrganismeDemandeur $organismeDemandeur, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $organismeDemandeur->setOrganismeDemandeurLibelle($data['organismeDemandeurLibelle']);

        $entityManager->flush();

        return new JsonResponse('Organisme demandeur mis à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/organisme-demandeurs/{id}', name: 'api_organisme_demandeur_delete', methods: ['DELETE'])]
    public function delete(OrganismeDemandeur $organismeDemandeur, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($organismeDemandeur);
        $entityManager->flush();

        return new JsonResponse('Organisme demandeur supprimé avec succès', Response::HTTP_OK);
    }
}
