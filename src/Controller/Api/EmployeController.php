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

class EmployeController extends AbstractController
{
    #[Route('/api/employes', name: 'api_employe_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        // Vérifier si les données nécessaires sont présentes
        $requiredFields = ['employeNom', 'employePrenom', 'employeDateNaissance', 'employeAdresse', 'employePrincipaleQualification', 'employeFormation', 'employeAffiliationDesAssociationsGroupPro', 'nationaliteId', 'situationFamilialeId', 'posteId', 'langueIds'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data) || empty($data[$field])) {
                return new JsonResponse(['message' => 'Données manquantes ou invalides.'], Response::HTTP_BAD_REQUEST);
            }
        }
    
        // Créer une nouvelle instance d'Employe
        $employe = new Employe();
    
        // Remplir les propriétés de l'entité avec les données reçues
        $employe->setEmployeNom($data['employeNom']);
        $employe->setEmployePrenom($data['employePrenom']);
        $employe->setEmployeDateNaissance(new \DateTime($data['employeDateNaissance']));
        $employe->setEmployeAdresse($data['employeAdresse']);
        $employe->setEmployePrincipaleQualification($data['employePrincipaleQualification']);
        $employe->setEmployeFormation($data['employeFormation']);
        $employe->setEmployeAffiliationDesAssociationsGroupPro($data['employeAffiliationDesAssociationsGroupPro']);
    
        // Récupérer les entités liées à partir des identifiants fournis
        $nationalite = $entityManager->getRepository(Nationalite::class)->find($data['nationaliteId']);
        $situationFamiliale = $entityManager->getRepository(SituationFamiliale::class)->find($data['situationFamilialeId']);
        $poste = $entityManager->getRepository(Poste::class)->find($data['posteId']);
    
        // Vérifier si les entités liées existent
        if (!$nationalite || !$situationFamiliale || !$poste) {
            return new JsonResponse(['message' => 'Une ou plusieurs entités liées n\'existent pas.'], Response::HTTP_BAD_REQUEST);
        }
    
        // Affecter les entités liées à l'Employe
        $employe->setNationalite($nationalite);
        $employe->setSituationFamiliale($situationFamiliale);
        $employe->setPoste($poste);
    
       // Récupérer les langues liées à partir des identifiants fournis
    $langues = [];
    foreach ($data['langueIds'] as $langueId) {
        $langue = $entityManager->getRepository(Langue::class)->find($langueId);
        if ($langue) {
            $employeLangue = new EmployeLangue();
            $employeLangue->setEmploye($employe);
            $employeLangue->setLangue($langue);
            $entityManager->persist($employeLangue);
            
            $langues[] = $langue;
        }
    }
    
        // Persist the entity
        $entityManager->persist($employe);
        $entityManager->flush();
    
        // Retourner une réponse JSON avec un message de succès
        return new JsonResponse(['message' => 'Employé créé avec succès'], Response::HTTP_CREATED);
    }
    
    #[Route('/api/employes/{id}', name: 'api_employe_get', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer l'employé par son identifiant
        $employe = $entityManager->getRepository(Employe::class)->find($id);

        // Vérifier si l'employé existe
        if (!$employe) {
            // Retourner une réponse JSON avec un message d'erreur si l'employé n'est pas trouvé
            return new JsonResponse(['message' => 'Employé non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Convertir l'objet Employe en tableau associatif
        $employeArray = [
            'id' => $employe->getId(),
            'employeNom' => $employe->getEmployeNom(),
            'employePrenom' => $employe->getEmployePrenom(),
            'employeDateNaissance' => $employe->getEmployeDateNaissance()->format('Y-m-d'),
            'employeAdresse' => $employe->getEmployeAdresse(),
            'employePrincipaleQualification' => $employe->getEmployePrincipaleQualification(),
            'employeFormation' => $employe->getEmployeFormation(),
            'employeAffiliationDesAssociationsGroupPro' => $employe->getEmployeAffiliationDesAssociationsGroupPro(),
            'nationalite' => $employe->getNationalite(),
            'situationFamiliale' => $employe->getSituationFamiliale(),
            'poste' => $employe->getPoste(),
            'langues' => $employe->getLangues()->toArray(),
        ];

        // Retourner une réponse JSON avec les données de l'employé
        return new JsonResponse($employeArray);
    }

    // Ajoutez les méthodes pour les autres actions CRUD (update, delete, list) en suivant le même schéma que les exemples précédents.
}
