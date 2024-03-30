<?php

namespace App\Controller\Api;

use App\Entity\ProjetPreuve;
use App\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProjetPreuveController extends AbstractController
{
    #[Route('/api/getAll/projet-preuves', name: 'api_projet_preuve_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
       $projetPreuves = $entityManager->getRepository(ProjetPreuve::class)->findAll();
        $data = [];

        foreach ($projetPreuves as $projetPreuve) {
            $data[] = $this->serializeProjetPreuve($projetPreuve);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/projet-preuves/{id}', name: 'api_projet_preuve_show', methods: ['GET'])]
    public function show(ProjetPreuve $projetPreuve): JsonResponse
    {
        return new JsonResponse($this->serializeProjetPreuve($projetPreuve), Response::HTTP_OK);
    }

    #[Route('/api/create/projet-preuves', name: 'api_projet_preuve_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $projetPreuve = new ProjetPreuve();
        $projetPreuve->setProjetPreuveLibelle($data['projetPreuveLibelle']);

        // Récupérer le projet associé
        $projet = $entityManager->getRepository(Projet::class)->find($data['projetId']);
        if (!$projet) {
            return new JsonResponse(['message' => 'Projet introuvable'], Response::HTTP_NOT_FOUND);
        }
        $projetPreuve->setProjet($projet);

        $entityManager->persist($projetPreuve);
        $entityManager->flush();

        return new JsonResponse('Projet preuve créé avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/put/projet-preuves/{id}', name: 'api_projet_preuve_update', methods: ['PUT'])]
    public function update(Request $request, ProjetPreuve $projetPreuve, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $projetPreuve->setProjetPreuveLibelle($data['projetPreuveLibelle']);

        // Mise à jour du projet associé
        if (isset($data['projetId'])) {
            $projet = $entityManager->getRepository(Projet::class)->find($data['projetId']);
            if (!$projet) {
                return new JsonResponse(['message' => 'Projet introuvable'], Response::HTTP_NOT_FOUND);
            }
            $projetPreuve->setProjet($projet);
        }

        $entityManager->flush();

        return new JsonResponse('Projet preuve mis à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/projet-preuves/{id}', name: 'api_projet_preuve_delete', methods: ['DELETE'])]
    public function delete(ProjetPreuve $projetPreuve, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($projetPreuve);
        $entityManager->flush();

        return new JsonResponse('Projet preuve supprimé avec succès', Response::HTTP_OK);
    }

    /**
     * Serialize ProjetPreuve entity to array.
     */
    private function serializeProjetPreuve(ProjetPreuve $projetPreuve): array
    {
        return [
            'projetPreuveId' => $projetPreuve->getId(),
            'projetPreuveLibelle' => $projetPreuve->getProjetPreuveLibelle(),
            'projetId' => $projetPreuve->getProjet()->getId(),
                // Ajoutez d'autres attributs du projet que vous souhaitez inclure
        
        ];
    }
}
