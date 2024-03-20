<?php

namespace App\Controller\Api;

use App\Entity\Projet;
use App\Entity\Client;
use App\Entity\Categorie;
use App\Entity\Lieu;
use App\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProjetController extends AbstractController
{
    #[Route('/api/create/projet', name: 'api_projet_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $projet = new Projet();
        $projet->setProjetLibelle($data['projetLibelle']);
        $projet->setProjetDescirption($data['projetDescirption']);
        $projet->setProjetReference($data['projetReference']);
        $projet->setProjetDateDemarrage(new \DateTime($data['projetDateDemarrage']));
        $projet->setProjetDateAchevement(new \DateTime($data['projetDateAchevement']));
        $projet->setProjetUrlFonctionnel($data['projetUrlFonctionnel']);
        $projet->setProjetDescriptionServiceEffectivementRendus($data['ProjetDescriptionServiceEffectivementRendus']);
    
        // Récupérer le lieu associé
        $lieu = $entityManager->getRepository(Lieu::class)->find($data['lieu_id']);
        if (!$lieu) {
            return new JsonResponse(['message' => 'Lieu introuvable'], Response::HTTP_NOT_FOUND);
        }
        $projet->setLieu($lieu);
    
        // Récupérer le client associé
        $client = $entityManager->getRepository(Client::class)->find($data['client_id']);
        if (!$client) {
            return new JsonResponse(['message' => 'Client introuvable'], Response::HTTP_NOT_FOUND);
        }
        $projet->setClient($client);
    
        // Ajouter les catégories associées au projet
        foreach ($data['categories'] as $categorieData) {
            $categorie = $entityManager->getRepository(Categorie::class)->find($categorieData['id']);
            if (!$categorie) {
                return new JsonResponse(['message' => 'Catégorie introuvable'], Response::HTTP_NOT_FOUND);
            }
            $projet->addCategorie($categorie);
        }

       // Ajouter les employés associés au projet
foreach ($data['employes'] as $employeData) {
    $employeId = $employeData['id'];
    $employe = $entityManager->getRepository(Employe::class)->find($employeId);
    dump($employe); // Vérifier si l'employé est récupéré correctement
    if (!$employe) {
        return new JsonResponse(['message' => 'Employé introuvable pour l\'ID : ' . $employeId], Response::HTTP_NOT_FOUND);
    }
    $projet->addEmploye($employe);
}
   
        $entityManager->persist($projet);
        $entityManager->flush();
    
        return new JsonResponse('Projet créé avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/projets', name: 'api_projet_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager): JsonResponse
    {
        $projets = $entityManager->getRepository(Projet::class)->findAll();
        $serializedProjets = [];
        foreach ($projets as $projet) {
            $serializedProjets[] = $this->serializeProjet($projet);
        }
        return new JsonResponse($serializedProjets, Response::HTTP_OK);
    }

    #[Route('/api/projets/{id}', name: 'api_projet_get_one', methods: ['GET'])]
    public function getOne($id, EntityManagerInterface $entityManager): JsonResponse
    {
        $projet = $entityManager->getRepository(Projet::class)->find($id);
        if (!$projet) {
            return new JsonResponse(['message' => 'Projet non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $serializedProjet = $this->serializeProjet($projet);
        return new JsonResponse($serializedProjet, Response::HTTP_OK);
    }

    #[Route('/api/projets/{id}', name: 'api_projet_delete', methods: ['DELETE'])]
    public function delete($id, EntityManagerInterface $entityManager): JsonResponse
    {
        $projet = $entityManager->getRepository(Projet::class)->find($id);
        if (!$projet) {
            return new JsonResponse(['message' => 'Projet non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($projet);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Projet supprimé avec succès'], Response::HTTP_OK);
    }

    /**
     * Serialize Projet entity to array.
     */
    private function serializeProjet(Projet $projet): array
    {
        $categories = [];
        foreach ($projet->getCategories() as $categorie) {
            $categories[] = $categorie->getCategorie();
        }

        return [
            'id' => $projet->getId(),
            'projetLibelle' => $projet->getProjetLibelle(),
            'projetDescirption' => $projet->getProjetDescirption(),
            'projetReference' => $projet->getProjetReference(),
            'projetDateDemarrage' => $projet->getProjetDateDemarrage()->format('Y-m-d'),
            'projetDateAchevement' => $projet->getProjetDateAchevement()->format('Y-m-d'),
            'projetUrlFonctionnel' => $projet->getProjetUrlFonctionnel(),
            'ProjetDescriptionServiceEffectivementRendus' => $projet->getProjetDescriptionServiceEffectivementRendus(),
            'clientId' =>  $projet->getClient() ?  $projet->getClient()->getClientId() : null,
            'lieuId' =>  $projet->getLieu() ?  $projet->getLieu()->getLieuId() : null,
            'categories' => $this->serializeCategories($projet->getCategories()),
            'employes' => $this->serializeEmployes($projet->getEmployes()), // Appel à la méthode serializeCategories pour obtenir les informations sur les catégories
           
        ];
    }
/**
 * Serialize Categories associated with Projet entity to array.
 */
private function serializeCategories($categories): array
{
    $serializedCategories = [];
    foreach ($categories as $categorie) {
        $serializedCategories[] = [
            'id' => $categorie->getId(),
            'categorie' => $categorie->getCategorie(),
            // Ajoutez d'autres propriétés de catégorie si nécessaire
        ];
    }}
  /**
 * Serialize Categories associated with Projet entity to array.
 */
private function serializeEmployes($employes): array
{
    $serializedEmployes = [];
    foreach ($employes as $employe) {
        $serializedEmployes[] = [
            'id' => $employe->getId(),
         
            // Ajoutez d'autres propriétés de catégorie si nécessaire
        ];
    }
}
    // Add other methods if needed
}
