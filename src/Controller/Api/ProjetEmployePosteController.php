<?php

namespace App\Controller\Api;

use App\Entity\ProjetEmployePoste;
use App\Entity\Employe;
use App\Entity\Projet;
use App\Entity\Poste;
use App\Repository\ProjetEmployePosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjetEmployePosteController extends AbstractController
{
    private $projetEmployePosteRepository;
    private $entityManager;

    public function __construct(ProjetEmployePosteRepository $projetEmployePosteRepository, EntityManagerInterface $entityManager)
    {
        $this->projetEmployePosteRepository = $projetEmployePosteRepository;
        $this->entityManager = $entityManager;
    }
    
    #[Route('/api/getAll/projet-employe-poste', name: 'api_projet_get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $projetEmployePostes = $this->projetEmployePosteRepository->findAll();
        $serializedProjetEmployePostes = [];
        foreach ($projetEmployePostes as $projetEmployePoste) {
            $serializedProjetEmployePostes[] = $this->serializeProjetEmployePoste($projetEmployePoste);
        }
        return new JsonResponse($serializedProjetEmployePostes, Response::HTTP_OK);
    }

    #[Route('/api/getOne/projet-employe-poste/{id}', name: 'api_projet_get_one', methods: ['GET'])]
    public function getOne($id): JsonResponse
    {
        $projetEmployePoste = $this->projetEmployePosteRepository->find($id);
        if (!$projetEmployePoste) {
            return new JsonResponse(['message' => 'Le ProjetEmployePoste spécifié n\'existe pas.'], JsonResponse::HTTP_NOT_FOUND);
        }
        $serializedProjetEmployePoste = $this->serializeProjetEmployePoste($projetEmployePoste);
        return new JsonResponse($serializedProjetEmployePoste, Response::HTTP_OK);
    }

    #[Route('/api/create/projet-employe-poste', name: 'api_projet_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $projetEmployePoste = new ProjetEmployePoste();
        $projetEmployePoste->setDuree($data['duree']);
        
        // Récupération de l'employé, du projet et du poste à partir de leurs IDs
        $employe = $entityManager->getRepository(Employe::class)->find($data['employe_id']);
        $projet = $entityManager->getRepository(Projet::class)->find($data['projet_id']);
        $poste = $entityManager->getRepository(Poste::class)->find($data['poste_id']);

        // Vérification si les entités sont valides
        if (!$employe || !$projet || !$poste) {
            return new JsonResponse(['message' => 'L\'employé, le projet ou le poste spécifié n\'existe pas.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $projetEmployePoste->setEmploye($employe);
        $projetEmployePoste->setProjet($projet);
        $projetEmployePoste->setPoste($poste);

        $entityManager->persist($projetEmployePoste);
        $entityManager->flush();

        return new JsonResponse($projetEmployePoste, JsonResponse::HTTP_CREATED);
    }

    /**
     * Serialize ProjetEmployePoste entity to array.
     */
    private function serializeProjetEmployePoste(ProjetEmployePoste $projetEmployePoste): array
    {
        return [
            'id' => $projetEmployePoste->getId(),
            'duree' => $projetEmployePoste->getDuree(),
            'employes' => $this->serializeEmployes($projetEmployePoste->getEmploye()),
            'projets' => $this->serializeProjets([$projetEmployePoste->getProjet()]),
            'poste' => [
                'poste_id' => $projetEmployePoste->getPoste()->getId(),
                // Ajoutez d'autres propriétés du poste si nécessaire
            ],
            // Ajoutez d'autres propriétés de l'entité ProjetEmployePoste si nécessaire
        ];
    }


    #[Route('/api/put/projet-employe-poste/{id}', name: 'api_projet_update', methods: ['PUT'])]
    public function update(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $projetEmployePoste = $this->projetEmployePosteRepository->find($id);

        if (!$projetEmployePoste) {
            return new JsonResponse(['message' => 'Le ProjetEmployePoste spécifié n\'existe pas.'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Mettre à jour les champs nécessaires du ProjetEmployePoste
        $projetEmployePoste->setDuree($data['duree']);

        // Mettre à jour la base de données
        $this->entityManager->flush();

        // Renvoyer une réponse JSON pour indiquer que la mise à jour a réussi
        return new JsonResponse(['message' => 'Le ProjetEmployePoste a été mis à jour avec succès.'], JsonResponse::HTTP_OK);
    }
    #[Route('/api/delete/projet-employe-poste/{id}', name: 'api_projet_delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $projetEmployePoste = $this->projetEmployePosteRepository->find($id);

        if (!$projetEmployePoste) {
            return new JsonResponse(['message' => 'Le ProjetEmployePoste spécifié n\'existe pas.'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Supprimer le ProjetEmployePoste de la base de données
        $this->entityManager->remove($projetEmployePoste);
        $this->entityManager->flush();

        // Renvoyer une réponse JSON pour indiquer que la suppression a réussi
        return new JsonResponse(['message' => 'Le ProjetEmployePoste a été supprimé avec succès.'], JsonResponse::HTTP_OK);
    }

    /**
     * Serialize Projet entities to array.
     */
    private function serializeProjets($projets): array
    {
        $serializedProjets = [];
        foreach ($projets as $projet) {
            $serializedProjets[] = [
                'id' => $projet->getId(),
                // Ajoutez d'autres propriétés du projet si nécessaire
            ];
        }
        return $serializedProjets;
    }

    private function serializeEmployes($employes): array
{
    $serializedEmployes = [];
    
    // Vérifier si $employes est une collection ou une seule entité
    if ($employes instanceof Employe) {
        $serializedEmployes[] = [
            'id' => $employes->getId(),
            // Ajoutez d'autres propriétés de l'employé si nécessaire
        ];
    } elseif (is_array($employes)) {
        foreach ($employes as $employe) {
            $serializedEmployes[] = [
                'id' => $employe->getId(),
                // Ajoutez d'autres propriétés de l'employé si nécessaire
            ];
        }
    }
    
    return $serializedEmployes;
}
}
