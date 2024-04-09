<?php

namespace App\Controller\Api;

use App\Entity\Employe;
use App\Entity\EmployeExperience;
use App\Entity\EmployeEducation;
use App\Entity\Langue;
use App\Entity\Nationalite;
use App\Entity\Poste;
use App\Entity\SituationFamiliale;
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

class EmployeController extends AbstractController
{
    #[Route('/api/create/employe', name: 'api_employe_create', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
{
    $this->checkToken($tokenStorage);
    $data = json_decode($request->getContent(), true);

    // Check if required keys exist in $data array
    if (!isset($data['personneContact']) || !isset($data['employeDateNaissance']) || !isset($data['employeAdresse']) ||
        !isset($data['employePrincipaleQualification']) || !isset($data['employeFormation']) || !isset($data['employeAffiliationDesAssociationsGroupPro'])) {
        return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
    }

    $employe = new Employe();
    $employe->setPersonneContact($data['personneContact']);
    $employe->setEmployeDateNaissance(new \DateTime($data['employeDateNaissance']));
    $employe->setEmployeAdresse($data['employeAdresse']);
    $employe->setEmployePrincipaleQualification($data['employePrincipaleQualification']);
    $employe->setEmployeFormation($data['employeFormation']);
    $employe->setEmployeAffiliationDesAssociationsGroupPro($data['employeAffiliationDesAssociationsGroupPro']);

    // Set the related entities if their IDs are provided
    if (isset($data['nationalite_id'])) {
        $nationalite = $entityManager->getRepository(Nationalite::class)->find($data['nationalite_id']);
        if (!$nationalite) {
            return new JsonResponse(['message' => 'Nationalité introuvable'], Response::HTTP_NOT_FOUND);
        }
        $employe->setNationalite($nationalite);
    }

    if (isset($data['situationFamiliale_id'])) {
        $situationFamiliale = $entityManager->getRepository(SituationFamiliale::class)->find($data['situationFamiliale_id']);
        if (!$situationFamiliale) {
            return new JsonResponse(['message' => 'Situation familiale introuvable'], Response::HTTP_NOT_FOUND);
        }
        $employe->setSituationFamiliale($situationFamiliale);
    }

    if (isset($data['poste_id'])) {
        $poste = $entityManager->getRepository(Poste::class)->find($data['poste_id']);
        if (!$poste) {
            return new JsonResponse(['message' => 'Poste introuvable'], Response::HTTP_NOT_FOUND);
        }
        $employe->setPoste($poste);
    }

    // Add languages associated with the employee
    if (isset($data['langue_ids'])) {
        $langueIds = $data['langue_ids'];
        foreach ($langueIds as $langueId) {
            $langue = $entityManager->getRepository(Langue::class)->find($langueId);
            if (!$langue) {
                return new JsonResponse(['message' => 'Langue introuvable'], Response::HTTP_NOT_FOUND);
            }
            $employe->addLangue($langue);
        }
    }

    $entityManager->persist($employe);
    $entityManager->flush();

    return new JsonResponse('Employé créé avec succès', Response::HTTP_CREATED);
}

#[Route('/api/getAll/employes', name: 'api_employe_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $employes = $entityManager->getRepository(Employe::class)->findAll();
        $serializedEmployes = [];
        foreach ($employes as $employe) {
            $serializedEmployes[] = $this->serializeEmploye($employe);
        }
        return new JsonResponse($serializedEmployes, Response::HTTP_OK);
    }
    private function serializeEmploye(Employe $employe): array
{
    $langues = [];
    foreach ($employe->getLangues() as $langue) {
        // Ajoutez toutes les propriétés de la langue que vous souhaitez inclure
        $langues[] = [
            'id' => $langue->getId(),
            'langueNom' => $langue->getLangueNom(), // Assurez-vous que c'est la bonne méthode pour récupérer le nom de la langue
            // Ajoutez d'autres propriétés de langue si nécessaire
        ];
    }

    return [
        'id' => $employe->getId(),
        'personneContact'=> $employe->getPersonneContact(),
        'employeDateNaissance' => $employe->getEmployeDateNaissance()->format('Y-m-d'),
        'employeAdresse' => $employe->getEmployeAdresse(),
        'employePrincipaleQualification' => $employe->getEmployePrincipaleQualification(),
        'employeFormation' => $employe->getEmployeFormation(),
        'employeAffiliationDesAssociationsGroupPro' => $employe->getEmployeAffiliationDesAssociationsGroupPro(),
        'nationalite' => $employe->getNationalite() ? $employe->getNationalite()->getId() : null,
        'situationFamiliale' => $employe->getSituationFamiliale() ? $employe->getSituationFamiliale()->getId() : null,
        'poste' => $employe->getPoste() ? $employe->getPoste()->getId() : null,
        'langues' => $langues, // Incluez les langues sérialisées ici
        // Ajoutez d'autres attributs si nécessaire
    ];
}

    
    #[Route('/api/put/employe/{id}', name: 'api_employe_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, $id, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $employe = $entityManager->getRepository(Employe::class)->find($id);
        if (!$employe) {
            return new JsonResponse(['message' => 'Employé non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $employe->setPersonneContact($data['personneContact'] ?? $employe->getPersonneContact());
        $employe->setEmployeDateNaissance(new \DateTime($data['employeDateNaissance'] ?? $employe->getEmployeDateNaissance()));
        $employe->setEmployeAdresse($data['employeAdresse'] ?? $employe->getEmployeAdresse());
        $employe->setEmployePrincipaleQualification($data['employePrincipaleQualification'] ?? $employe->getEmployePrincipaleQualification());
        $employe->setEmployeFormation($data['employeFormation'] ?? $employe->getEmployeFormation());
        $employe->setEmployeAffiliationDesAssociationsGroupPro($data['employeAffiliationDesAssociationsGroupPro'] ?? $employe->getEmployeAffiliationDesAssociationsGroupPro());

        // Set the related entities
        $nationaliteId = $data['nationalite_id'] ?? null;
        if ($nationaliteId) {
            $nationalite = $entityManager->getRepository(Nationalite::class)->find($nationaliteId);
            if (!$nationalite) {
                return new JsonResponse(['message' => 'Nationalité introuvable'], Response::HTTP_NOT_FOUND);
            }
            $employe->setNationalite($nationalite);
        }

        $situationFamilialeId = $data['situationFamiliale_id'] ?? null;
        if ($situationFamilialeId) {
            $situationFamiliale = $entityManager->getRepository(SituationFamiliale::class)->find($situationFamilialeId);
            if (!$situationFamiliale) {
                return new JsonResponse(['message' => 'Situation familiale introuvable'], Response::HTTP_NOT_FOUND);
            }
            $employe->setSituationFamiliale($situationFamiliale);
        }

        $posteId = $data['poste_id'] ?? null;
        if ($posteId) {
            $poste = $entityManager->getRepository(Poste::class)->find($posteId);
            if (!$poste) {
                return new JsonResponse(['message' => 'Poste introuvable'], Response::HTTP_NOT_FOUND);
            }
            $employe->setPoste($poste);
        }

        // Update languages associated with the employee
        if (isset($data['langue_ids'])) {
            $employe->getLangues()->clear(); // Clear existing languages
            $langueIds = $data['langue_ids'];
            foreach ($langueIds as $langueId) {
                $langue = $entityManager->getRepository(Langue::class)->find($langueId);
                if (!$langue) {
                    return new JsonResponse(['message' => 'Langue introuvable'], Response::HTTP_NOT_FOUND);
                }
                $employe->addLangue($langue);
            }
        }

        $entityManager->flush();

        return new JsonResponse('Employé mis à jour avec succès', Response::HTTP_OK);
    }
    #[Route('/api/delete/employe/{id}', name: 'api_employe_delete', methods: ['DELETE'])]
    public function delete($id, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $employe = $entityManager->getRepository(Employe::class)->find($id);
        if (!$employe) {
            return new JsonResponse(['message' => 'Employé non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($employe);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Employé supprimé avec succès'], Response::HTTP_OK);
    }
    #[Route('/api/get/employe/{id}', name: 'api_employe_get_one', methods: ['GET'])]
    public function getOne($id, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        $this->checkToken($tokenStorage);
        $employe = $entityManager->getRepository(Employe::class)->find($id);
        if (!$employe) {
            return new JsonResponse(['message' => 'Employé non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $serializedEmploye = $this->serializeEmploye($employe);
        return new JsonResponse($serializedEmploye, Response::HTTP_OK);
    }
    private function serializeLangues($langues): array
    {
        $serializedLangues = [];
        foreach ($langues as $langue) {
            $serializedLangues[] = [
                'id' => $langue->getId(),
                'langue' => $langue->getLangueNom(),
                // Ajoutez d'autres propriétés de langue si nécessaire
            ];
        }
        return $serializedLangues;
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
