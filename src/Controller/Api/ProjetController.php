<?php

namespace App\Controller\Api;

use App\Entity\Projet;
use App\Entity\Client;
use App\Entity\Categorie;
use App\Entity\Lieu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProjetController extends AbstractController
{
    #[Route('/api/create/projet', name: 'api_projet_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);
    
        // Vérifier si les champs requis existent dans les données
        $requiredFields = ['projetLibelle', 'projetDescription', 'projetReference', 'projetDateDemarrage', 'projetDateAchevement', 'projetUrlFonctionnel', 'projetDescriptionServiceEffectivementRendus'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
            }
        }
    
        // Créer une nouvelle instance de Projet
        $projet = new Projet();
        $projet->setProjetLibelle($data['projetLibelle']);
        $projet->setProjetDescription($data['projetDescription']);
        $projet->setProjetReference($data['projetReference']);
        $projet->setProjetDateDemarrage(new \DateTime($data['projetDateDemarrage']));
        $projet->setProjetDateAchevement(new \DateTime($data['projetDateAchevement']));
        $projet->setProjetUrlFonctionnel($data['projetUrlFonctionnel']);
        $projet->setProjetDescriptionServiceEffectivementRendus($data['projetDescriptionServiceEffectivementRendus']);
    
        // Associer le lieu
        if (isset($data['lieuId'])) {
            $lieu = $entityManager->getRepository(Lieu::class)->find($data['lieuId']);
            if (!$lieu) {
                return new JsonResponse(['message' => 'Lieu not found'], Response::HTTP_NOT_FOUND);
            }
            $projet->setLieu($lieu);
        }
    
        // Associer le client
        if (isset($data['clientId'])) {
            $client = $entityManager->getRepository(Client::class)->find($data['clientId']);
            if (!$client) {
                return new JsonResponse(['message' => 'Client not found'], Response::HTTP_NOT_FOUND);
            }
            $projet->setClient($client);
        }
    
        // Associer le client
        if (isset($data['categorieId'])) {
            $categorie = $entityManager->getRepository(Categorie::class)->find($data['categorieId']);
            if (!$categorie) {
                return new JsonResponse(['message' => 'categorie not found'], Response::HTTP_NOT_FOUND);
            }
            $projet->setCategorie($categorie);
        }
    
        // Persister et flush le projet
        $entityManager->persist($projet);
        $entityManager->flush();
    
        return new JsonResponse('Projet créé avec succès', Response::HTTP_CREATED);
    }
    
    #[Route('/api/getAll/projets', name: 'api_projet_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);

        $projetRepository = $entityManager->getRepository(Projet::class);
        $projets = $projetRepository->findBy([], ['projetLibelle' => 'ASC']);

        $serializedProjets = [];
        foreach ($projets as $projet) {
            $serializedProjets[] = $this->serializeProjetNom($projet);
        }

        return new JsonResponse($serializedProjets, Response::HTTP_OK);
    }

    #[Route('/api/get/projet/{id}', name: 'api_projet_get_one_details', methods: ['GET'])]
    public function getProjetDetails($id, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $projet = $entityManager->getRepository(Projet::class)->find($id);
        if (!$projet) {
            return new JsonResponse(['message' => 'Projet non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $serializedProjet = $this->serializeProjet($projet);
        return new JsonResponse($serializedProjet, Response::HTTP_OK);
    }

    #[Route('/api/getOne/projet/{id}', name: 'api_projet_get_one', methods: ['GET'])]
    public function getProjetOne($id, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $projet = $entityManager->getRepository(Projet::class)->find($id);
        if (!$projet) {
            return new JsonResponse(['message' => 'Projet non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $serializedProjet = $this->serializeProjetNom($projet);
        return new JsonResponse($serializedProjet, Response::HTTP_OK);
    }

    #[Route('/api/delete/projet/{id}', name: 'api_projet_delete', methods: ['DELETE'])]
    public function delete($id, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $projet = $entityManager->getRepository(Projet::class)->find($id);
        if (!$projet) {
            return new JsonResponse(['message' => 'Projet non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($projet);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Projet supprimé avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/edit/projet/{id}', name: 'api_projet_edit', methods: ['PUT'])]
    public function edit($id, Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);
        $projet = $entityManager->getRepository(Projet::class)->find($id);
        if (!$projet) {
            return new JsonResponse(['message' => 'Projet non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Check if required keys exist in $data array
        if (!isset($data['projetLibelle']) || !isset($data['projetDescription']) || !isset($data['projetReference']) ||
            !isset($data['projetDateDemarrage']) || !isset($data['projetDateAchevement']) || !isset($data['projetUrlFonctionnel']) || !isset($data['projetDescriptionServiceEffectivementRendus'])) {
            return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $projet->setProjetLibelle($data['projetLibelle']);
        $projet->setProjetDescription($data['projetDescription']);
        $projet->setProjetReference($data['projetReference']);
        $projet->setProjetDateDemarrage(new \DateTime($data['projetDateDemarrage']));
        $projet->setProjetDateAchevement(new \DateTime($data['projetDateAchevement']));
        $projet->setProjetUrlFonctionnel($data['projetUrlFonctionnel']);
        $projet->setProjetDescriptionServiceEffectivementRendus($data['projetDescriptionServiceEffectivementRendus']);

        // Set the related entities if their IDs are provided
        if (isset($data['lieuId'])) {
            $lieu = $entityManager->getRepository(Lieu::class)->find($data['lieuId']);
            if (!$lieu) {
                return new JsonResponse(['message' => 'Lieu introuvable'], Response::HTTP_NOT_FOUND);
            }
            $projet->setLieu($lieu);
        }

        if (isset($data['clientId'])) {
            $client = $entityManager->getRepository(Client::class)->find($data['clientId']);
            if (!$client) {
                return new JsonResponse(['message' => 'Client introuvable'], Response::HTTP_NOT_FOUND);
            }
            $projet->setClient($client);
        }

         // Associer le client
         if (isset($data['categorieId'])) {
            $categorie = $entityManager->getRepository(Categorie::class)->find($data['categorieId']);
            if (!$categorie) {
                return new JsonResponse(['message' => 'categorie not found'], Response::HTTP_NOT_FOUND);
            }
            $projet->setCategorie($categorie);
        }
    

        $entityManager->flush();

        return new JsonResponse(['message' => 'Projet modifié avec succès'], Response::HTTP_OK);
    }

    public function checkToken(TokenStorageInterface $tokenStorage): void
    {
        // Récupérer le token d'authentification de Symfony
        $token = $tokenStorage->getToken();

        // Vérifier si le token d'authentification est présent et est de type TokenInterface
        if (!$token instanceof TokenInterface) {
            throw new AccessDeniedHttpException('Token d\'authentification manquant ou invalide');
        }
    }

    // Utility method to serialize projet entity
    private function serializeProjet(Projet $projet): array
    {
        

        return [
            'id' => $projet->getId(),
            'projetLibelle' => $projet->getProjetLibelle(),
            'projetDescription' => $projet->getProjetDescription(),
            'projetReference' => $projet->getProjetReference(),
            'projetDateDemarrage' => $projet->getProjetDateDemarrage()->format('Y-m-d'),
            'projetDateAchevement' => $projet->getProjetDateAchevement()->format('Y-m-d'),
            'projetUrlFonctionnel' => $projet->getProjetUrlFonctionnel(),
            'projetDescriptionServiceEffectivementRendus' => $projet->getProjetDescriptionServiceEffectivementRendus(),
            'lieuId' => $projet->getLieu() ? $projet->getLieu()->getId() : null,
            'clientId' => $projet->getClient() ? $projet->getClient()->getId() : null,
            'categorieId' => $projet->getCategorie() ? $projet->getCategorie()->getId() : null,// Incluez les catégories sérialisées ici
            // Ajoutez d'autres attributs si nécessaire
        ];
    }

    private function serializeProjetNom(Projet $projet): array
    {
       
        return [
            'id' => $projet->getId(),
            'projetLibelle' => $projet->getProjetLibelle(),
            'projetDescription' => $projet->getProjetDescription(),
            'projetReference' => $projet->getProjetReference(),
            'projetDateDemarrage' => $projet->getProjetDateDemarrage()->format('Y-m-d'),
            'projetDateAchevement' => $projet->getProjetDateAchevement()->format('Y-m-d'),
            'projetUrlFonctionnel' => $projet->getProjetUrlFonctionnel(),
            'projetDescriptionServiceEffectivementRendus' => $projet->getProjetDescriptionServiceEffectivementRendus(),
            'lieu' => $projet->getLieu() ? $projet->getLieu()->getLieuNom() : null,
            'client' => $projet->getClient() ? $projet->getClient()->getPersonneContact() : null,
            'categorie' => $projet->getCategorie() ? $projet->getCategorie()->getCategorieNom() : null,
            // Ajoutez d'autres attributs si nécessaire
            // Ajoutez d'autres attributs si nécessaire
        ];
    }
}
