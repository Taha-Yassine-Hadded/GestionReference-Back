<?php

namespace App\Controller\Api;

use App\Entity\Categorie;
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

class CategorieController extends AbstractController
{
    #[Route('/api/create/categorie', name: 'api_categorie_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $categorie = new Categorie();
        $categorie->setCategorie($data['categorie']); // Assuming 'name' is the field for the category name

        $entityManager->persist($categorie);
        $entityManager->flush();

        return new JsonResponse('Catégorie créée avec succès', Response::HTTP_CREATED);
    }

    #[Route('/api/getAll/categorie', name: 'api_categorie_get_all', methods: ['GET'])]
public function index(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
{
    $this->checkToken($tokenStorage);
    
    // Récupérer les catégories triées par ordre alphabétique
    $categories = $entityManager->getRepository(Categorie::class)->findBy([], ['categorie' => 'ASC']);
    
    $data = [];

    foreach ($categories as $categorie) {
        $data[] = [
            'id_categorie' => $categorie->getId(),
            'categorie' => $categorie->getCategorie(),
        ];
    }

    return new JsonResponse($data, Response::HTTP_OK);
}

    #[Route('/api/get/categorie/{id}', name: 'api_categorie_get', methods: ['GET'])]
    public function show(Categorie $categorie, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = [
            'id_categorie' => $categorie->getId(),
            'categorie' => $categorie->getCategorie(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/put/categorie/{id}', name: 'api_categorie_update', methods: ['PUT'])]
    public function update(Request $request, Categorie $categorie, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $categorie->setCategorie($data['categorie']); // Assuming 'name' is the field for the category name

        $entityManager->flush();

        return new JsonResponse('Catégorie mise à jour avec succès', Response::HTTP_OK);
    }

    #[Route('/api/delete/categorie/{id}', name: 'api_categorie_delete', methods: ['DELETE'])]
    public function delete(Categorie $categorie, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse('Catégorie supprimée avec succès', Response::HTTP_OK);
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
