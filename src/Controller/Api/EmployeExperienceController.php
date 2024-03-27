<?php

namespace App\Controller\Api;

use App\Entity\Employe;
use App\Entity\EmployeExperience;
use App\Repository\EmployeExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeExperienceController extends AbstractController
{
    #[Route('/api/getAll/employe/experiences', name: 'api_employe_experience_index', methods: ['GET'])]
    public function index(EmployeExperienceRepository $repository): JsonResponse
    {
        $experiences = $repository->findAll();

        $data = [];
        foreach ($experiences as $experience) {
            $data[] = $this->serializeEmployeExperience($experience);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/employe-experiences/{id}', name: 'api_employe_experience_get', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $employeExperience = $entityManager->getRepository(EmployeExperience::class)->find($id);
    
        if (!$employeExperience) {
            return new JsonResponse(['message' => 'Expérience employé introuvable'], Response::HTTP_NOT_FOUND);
        }
    
        return new JsonResponse($employeExperience);
    }
    

    #[Route('/api/create/employe-experiences', name: 'api_employe_experience_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $employeExperience = new EmployeExperience();
        $employeExperience->setEmployeExperiencePoste($data['employeExperiencePoste']);
        $employeExperience->setEmployeExperienceSociete($data['employeExperienceSociete']);
        $employeExperience->setEmployeExperienceOragnismeEmployeur($data['employeExperienceOragnismeEmployeur']);
        $employeExperience->setEmployeExperiencePeriode(new \DateTime($data['employeExperiencePeriode']));
        $employeExperience->setEmployeExperienceFonctionOccupe($data['employeExperienceFonctionOccupe']);
    
        // Récupérer l'employé associé
        $employe = $entityManager->getRepository(Employe::class)->find($data['employeId']);
        if (!$employe) {
            return new JsonResponse(['message' => 'Employé introuvable'], Response::HTTP_NOT_FOUND);
        }
        $employeExperience->setEmploye($employe);
    
        $entityManager->persist($employeExperience);
        $entityManager->flush();
    
        return new JsonResponse('Expérience employé créée avec succès', Response::HTTP_CREATED);
    }
    
    #[Route('/api/put/employe-experiences/{id}', name: 'api_employe_experience_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $employeExperience = $entityManager->getRepository(EmployeExperience::class)->find($id);

        if (!$employeExperience) {
            return new JsonResponse(['message' => 'Expérience employé introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Mise à jour des propriétés de l'entité
        $employeExperience->setEmployeExperiencePoste($data['employeExperiencePoste']);
        $employeExperience->setEmployeExperienceSociete($data['employeExperienceSociete']);
        $employeExperience->setEmployeExperienceOragnismeEmployeur($data['employeExperienceOragnismeEmployeur']);
        $employeExperience->setEmployeExperiencePeriode(new \DateTime($data['employeExperiencePeriode']));
        $employeExperience->setEmployeExperienceFonctionOccupe($data['employeExperienceFonctionOccupe']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Expérience employé mise à jour avec succès']);
    }

    #[Route('/api/employe-experiences/{id}', name: 'api_employe_experience_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $employeExperience = $entityManager->getRepository(EmployeExperience::class)->find($id);

        if (!$employeExperience) {
            return new JsonResponse(['message' => 'Expérience employé introuvable'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($employeExperience);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Expérience employé supprimée avec succès']);
    }

   
    /**
     * Serialize EmployeEducation entity to array.
     */
    private function serializeEmployeExperience(EmployeExperience $employeExperience): array
    {
        return [
            'employeExperienceId' => $employeExperience->getId(),
            'employeExperiencePoste' => $employeExperience->getEmployeExperiencePoste(),
            'employeExperienceSociete' => $employeExperience->getEmployeExperienceSociete(),
            'employeExperienceOragnismeEmployeur' => $employeExperience->getEmployeExperienceOragnismeEmployeur(),
            'employeExperiencePeriode' => $employeExperience->getEmployeExperiencePeriode(),
            'employeExperienceFonctionOccupe' => $employeExperience->getEmployeExperienceFonctionOccupe(),
            'employeId' => $employeExperience->getEmploye()->getId(),
            // Ajoutez d'autres attributs de l'entité que vous souhaitez inclure dans la réponse JSON
        ];
    }
}
