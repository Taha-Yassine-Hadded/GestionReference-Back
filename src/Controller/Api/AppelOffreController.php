<?php

namespace App\Controller\Api;

use App\Controller\Api\UserController;
use App\Entity\AppelOffre;
use App\Entity\AppelOffreType;
use App\Entity\MoyenLivraison;
use App\Entity\OrganismeDemandeur;
use App\Repository\AppelOffreRepository;
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

class AppelOffreController extends AbstractController
{

    
    #[Route('/api/create/appel-offres', name: 'api_appel_offres_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Créer une nouvelle instance d'AppelOffre
        $appelOffre = new AppelOffre();

        // Remplir les propriétés de l'entité avec les données reçues
        $appelOffre->setAppelOffreDevis($data['appelOffreDevis']);
        $appelOffre->setAppelOffreObjet($data['appelOffreObjet']);
        $appelOffre->setAppelOffreDateRemise(new \DateTime($data['appelOffreDateRemise']));
        $appelOffre->setAppelOffreRetire($data['appelOffreRetire']);
        $appelOffre->setAppelOffreParticipation($data['appelOffreParticipation']);
        $appelOffre->setAppelOffreEtat($data['appelOffreEtat']);

        // Récupérer les entités liées à partir des identifiants fournis
        $appelOffreType = $entityManager->getRepository(AppelOffreType::class)->find($data['appelOffreTypeId']);
        $moyenLivraison = $entityManager->getRepository(MoyenLivraison::class)->find($data['moyenLivraisonId']);
        $organismeDemandeur = $entityManager->getRepository(OrganismeDemandeur::class)->find($data['organismeDemandeurId']);

        // Vérifier si les entités liées existent
        if (!$appelOffreType || !$moyenLivraison || !$organismeDemandeur) {
            return new JsonResponse(['message' => 'Une ou plusieurs entités liées n\'existent pas.'], Response::HTTP_BAD_REQUEST);
        }

        // Affecter les entités liées à l'AppelOffre
        $appelOffre->setAppelOffreType($appelOffreType);
        $appelOffre->setMoyenLivraison($moyenLivraison);
        $appelOffre->setOrganismeDemandeur($organismeDemandeur);

        // Persist the entity
        $entityManager->persist($appelOffre);
        $entityManager->flush();

        // Retourner une réponse JSON avec un message de succès
        return new JsonResponse(['message' => 'Appel d\'offre créé avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/get/appel-offres/{id}', name: 'api_appel_offres_get', methods: ['GET'])]
    public function getOne(int $id, AppelOffreRepository $appelOffreRepository, Request $request, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);

        $appelOffre = $appelOffreRepository->find($id);
    
        if (!$appelOffre) {
            return new JsonResponse(['message' => 'Appel d\'offre non trouvé'], Response::HTTP_NOT_FOUND);
        }
    
        $appelOffreArray = $this->serializeAppelOffre($appelOffre);
    
        return new JsonResponse($appelOffreArray);
    }

    #[Route('/api/put/appel-offres/{id}', name: 'api_appel_offres_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, AppelOffreRepository $appelOffreRepository, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);
        $appelOffre = $appelOffreRepository->find($id);
    
        if (!$appelOffre) {
            return new JsonResponse(['message' => 'Appel d\'offre non trouvé'], Response::HTTP_NOT_FOUND);
        }
    
        // Récupérer les entités liées à partir des identifiants fournis
        $appelOffreType = $entityManager->getRepository(AppelOffreType::class)->find($data['appelOffreTypeId']);
        $moyenLivraison = $entityManager->getRepository(MoyenLivraison::class)->find($data['moyenLivraisonId']);
        $organismeDemandeur = $entityManager->getRepository(OrganismeDemandeur::class)->find($data['organismeDemandeurId']);
    
        // Vérifier si les entités liées existent
        if (!$appelOffreType || !$moyenLivraison || !$organismeDemandeur) {
            return new JsonResponse(['message' => 'Une ou plusieurs entités liées n\'existent pas.'], Response::HTTP_BAD_REQUEST);
        }
    
        // Mettre à jour les propriétés de l'entité avec les données reçues
        $appelOffre->setAppelOffreDevis($data['appelOffreDevis'] ?? $appelOffre->getAppelOffreDevis());
        $appelOffre->setAppelOffreObjet($data['appelOffreObjet'] ?? $appelOffre->getAppelOffreObjet());
        $appelOffre->setAppelOffreDateRemise(new \DateTime($data['appelOffreDateRemise']));
        $appelOffre->setAppelOffreRetire($data['appelOffreRetire']);
        $appelOffre->setAppelOffreParticipation($data['appelOffreParticipation']);
        $appelOffre->setAppelOffreEtat($data['appelOffreEtat']);
        $appelOffre->setAppelOffreType($appelOffreType);
        $appelOffre->setMoyenLivraison($moyenLivraison);
        $appelOffre->setOrganismeDemandeur($organismeDemandeur);
        
        // Enregistrer les changements dans la base de données
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Appel d\'offre mis à jour avec succès'], Response::HTTP_OK);
    }
    

    #[Route('/api/delete/appel-offres/{id}', name: 'api_appel_offres_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager, AppelOffreRepository $appelOffreRepository, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $appelOffre = $appelOffreRepository->find($id);

        if (!$appelOffre) {
            return new JsonResponse(['message' => 'Appel d\'offre non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Supprimer l'entité de la base de données
        $entityManager->remove($appelOffre);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Appel d\'offre supprimé avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/getAll/appelOffres', name: 'api_AppelOffre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager , TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        // Retrieve all AppelOffre entities from the database
       
        $appelOffres = $entityManager->getRepository(AppelOffre::class)->findAll();
        
        // Initialize an array to store serialized data
        $data = [];
    
        // Serialize each AppelOffre entity and add it to the array
        foreach ($appelOffres as $appelOffre) {
            $data[] = $this->serializeAppelOffre($appelOffre);
        }
    
        // Return the serialized data as a JSON response
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * Serialize AppelOffre entity to array.
     */
    private function serializeAppelOffre(AppelOffre $appelOffre): array
    {
        return [
            'appelOffreId' => $appelOffre->getId(),
            'appelOffreDevis' => $appelOffre->getAppelOffreDevis(),
            'appelOffreObjet' => $appelOffre->getAppelOffreObjet(),
            'appelOffreDateRemise' => $appelOffre->getAppelOffreDateRemise() ? $appelOffre->getAppelOffreDateRemise()->format('Y-m-d') : null,
            'appelOffreRetire' => $appelOffre->getAppelOffreRetire(),
            'appelOffreParticipation' => $appelOffre->getAppelOffreParticipation(),
            'appelOffreEtat' => $appelOffre->getAppelOffreEtat(),
            'appelOffreTypeId' => $appelOffre->getAppelOffreType() ? $appelOffre->getAppelOffreType()->getId() : null,
            'moyenLivraisonId' => $appelOffre->getMoyenLivraison() ? $appelOffre->getMoyenLivraison()->getId() : null,
            'organismeDemandeurId' => $appelOffre->getOrganismeDemandeur() ? $appelOffre->getOrganismeDemandeur()->getId() : null,
            // Ajoutez d'autres attributs de l'entité que vous souhaitez inclure dans la réponse JSON
        ];
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
}