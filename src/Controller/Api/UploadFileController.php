<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UploadFile;
use App\Entity\ProjetPreuve;

class UploadFileController extends AbstractController
{
    #[Route('/api/uploadfiles', name: 'api_uploadfile_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Vérifier si un fichier a été envoyé
        if (!$request->files->has('fichier')) {
            return new JsonResponse(['message' => 'Aucun fichier n\'a été envoyé'], Response::HTTP_BAD_REQUEST);
        }
    
        // Récupérer le fichier envoyé dans la requête
        $uploadedFile = $request->files->get('fichier');
    
        // Créer une nouvelle instance de UploadFile
        $uploadFile = new UploadFile();
        
        // Assurez-vous que vous avez configuré votre entité UploadFile pour gérer le fichier correctement
        // Par exemple, utilisez des méthodes comme move() pour déplacer le fichier vers le dossier de stockage, et affectez le chemin du fichier à l'entité UploadFile.
        // Ici, nous devons vérifier si $uploadedFile est instance de UploadedFile.
        if (!$uploadedFile instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            return new JsonResponse(['message' => 'Fichier invalide'], Response::HTTP_BAD_REQUEST);
        }
    
        // Par exemple :
        $directory = '/chemin/vers/le/dossier/de/stockage';
        $filename = uniqid() . '.' . $uploadedFile->guessExtension(); // Générer un nom de fichier unique
        
        try {
            $uploadedFile->move($directory, $filename);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors du déplacement du fichier'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        // Assigner le chemin du fichier à l'entité UploadFile
        $uploadFile->setFichier($directory . '/' . $filename);
    
        // Si l'entité ProjetPreuve est envoyée dans la requête, vous devez le gérer aussi
        if ($request->request->has('projetPreuveId')) {
            $projetPreuveId = $request->request->get('projetPreuveId');
            $projetPreuve = $entityManager->getRepository(ProjetPreuve::class)->find($projetPreuveId);
            if (!$projetPreuve) {
                return new JsonResponse(['message' => 'ProjetPreuve introuvable'], Response::HTTP_NOT_FOUND);
            }
            $uploadFile->setProjetPreuve($projetPreuve);
        }
    
        // Persister l'entité dans la base de données
        $entityManager->persist($uploadFile);
        $entityManager->flush();
    
        // Renvoyer une réponse indiquant que l'entité a été créée avec succès
        return new JsonResponse(['message' => 'UploadFile créé avec succès'], Response::HTTP_CREATED);
    }
}
