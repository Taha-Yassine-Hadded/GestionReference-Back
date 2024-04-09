<?php

namespace App\Controller\Api;

use App\Entity\Employe;
use App\Entity\EmployeEducation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EmployeEducationController extends AbstractController
{
    #[Route('/api/getAll/employe-educations', name: 'api_employe_education_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $employeEducations = $entityManager->getRepository(EmployeEducation::class)->findAll();
        $data = [];

        foreach ($employeEducations as $employeEducation) {
            $data[] = $this->serializeEmployeEducation($employeEducation);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/employe-educations/{id}', name: 'api_employe_education_show', methods: ['GET'])]
    public function show(EmployeEducation $employeEducation, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        return new JsonResponse($this->serializeEmployeEducation($employeEducation), Response::HTTP_OK);
    }

    #[Route('/api/create/employe-educations', name: 'api_employe_education_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);
    
        $employeEducation = new EmployeEducation();
        $employeEducation->setEmployeEducationNatureEtudes($data['employeEducationNatureEtudes']);
        $employeEducation->setEmployeEducationEtablissement($data['employeEducationEtablissement']);
        $employeEducation->setEmployeEducationDiplomes($data['employeEducationDiplomes']);
        $employeEducation->setEmployeEducationAnneeObtention(new \DateTime($data['employeEducationAnneeObtention']));
    
        // Récupérer l'employé associé
        $employe = $entityManager->getRepository(Employe::class)->find($data['employeId']);
        if (!$employe) {
            return new JsonResponse(['message' => 'Employé introuvable'], Response::HTTP_NOT_FOUND);
        }
        $employeEducation->setEmploye($employe);
    
        $entityManager->persist($employeEducation);
        $entityManager->flush();
    
        return new JsonResponse('Formation employé créée avec succès', Response::HTTP_CREATED);
    }
    

    #[Route('/api/put/employe-educations/{id}', name: 'api_employe_education_update', methods: ['PUT'])]
    public function update(Request $request, EmployeEducation $employeEducation, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);
    
        $employeEducation->setEmployeEducationNatureEtudes($data['employeEducationNatureEtudes']);
        $employeEducation->setEmployeEducationEtablissement($data['employeEducationEtablissement']);
        $employeEducation->setEmployeEducationDiplomes($data['employeEducationDiplomes']);
        // Assurez-vous que la date envoyée est valide et dans le bon format
        $employeEducation->setEmployeEducationAnneeObtention(new \DateTime($data['employeEducationAnneeObtention']));
    
        // Mise à jour de l'employé associé
        if (isset($data['employeId'])) {
            $employe = $entityManager->getRepository(Employe::class)->find($data['employeId']);
            if (!$employe) {
                return new JsonResponse(['message' => 'Employé introuvable'], Response::HTTP_NOT_FOUND);
            }
            $employeEducation->setEmploye($employe);
        }
    
        $entityManager->flush();
    
        return new JsonResponse('Formation employé mise à jour avec succès', Response::HTTP_OK);
    }
    
    #[Route('/api/delete/employe-educations/{id}', name: 'api_employe_education_delete', methods: ['DELETE'])]
    public function delete(EmployeEducation $employeEducation, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $entityManager->remove($employeEducation);
        $entityManager->flush();

        return new JsonResponse('Formation employé supprimée avec succès', Response::HTTP_OK);
    }

    /**
     * Serialize EmployeEducation entity to array.
     */
    private function serializeEmployeEducation(EmployeEducation $employeEducation): array
    {
        return [
            'employeEducationId' => $employeEducation->getId(),
            'employeEducationNatureEtudes' => $employeEducation->getEmployeEducationNatureEtudes(),
            'employeEducationEtablissement' => $employeEducation->getEmployeEducationEtablissement(),
            'employeEducationDiplomes' => $employeEducation->getEmployeEducationDiplomes(),
            'employeEducationAnneeObtention' => $employeEducation->getEmployeEducationAnneeObtention()->format('Y-m-d'),
            'employeId' => $employeEducation->getEmploye()->getId(),
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

