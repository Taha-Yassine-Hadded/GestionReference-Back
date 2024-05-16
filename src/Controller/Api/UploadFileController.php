<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\UploadFile;
use App\Entity\ProjetPreuve;
use App\Repository\UploadFileRepository;
use Symfony\Component\Filesystem\Filesystem;

class UploadFileController extends AbstractController
{   
    private $entityManager;
    private $UploadFileRepository;

    public function __construct(EntityManagerInterface $entityManager, UploadFileRepository $uploadFileRepository)
    {
        $this->entityManager = $entityManager;
        $this->uploadFileRepository = $uploadFileRepository;
    }
    #[Route('/api/upload', name: 'api_uploadfile_create', methods: ['POST'])]
    public function upload(Request $request): Response
    {
        $uploadedFile = $request->files->get('file');
        $idPreuveProjet = $request->request->get('projetPreuveId'); // Récupérer l'ID de la preuve du projet depuis la requête
    
        if (!$uploadedFile || !$idPreuveProjet) {
            return $this->json(['error' => 'Paramètres manquants'], 400);
        }

        $originalFileName = $uploadedFile->getClientOriginalName();

        try {
            $uploadedFile->move(
                $this->getParameter('uploads_directory'),
                $originalFileName
            );
        } catch (FileException $e) {
            return $this->json(['error' => 'Erreur lors du téléchargement du fichier'], 500);
        }

        $uploadFile = new UploadFile();
        $uploadFile->setFileName($originalFileName);
        $uploadFile->setFilePath('/uploads/' . $originalFileName);
        $uploadFile->setProjetPreuve($this->entityManager->getReference(ProjetPreuve::class, $idPreuveProjet)); // Associer l'ID de la preuve du projet
        
        $this->entityManager->persist($uploadFile);
        $this->entityManager->flush();

        return $this->json(['id' => $uploadFile->getId()], 200);
    }

    #[Route('/api/upload/{id}', name: 'api_uploadfile_update', methods: ['PUT'])]
    public function updateUpload(Request $request, int $id): Response
    {
        $upload = $this->entityManager->getRepository(UploadFile::class)->find($id);
        $idPreuveProjet = $request->request->get('idPreuveProjet'); // Récupérer l'ID de la preuve du projet depuis la requête

        if (!$upload || !$idPreuveProjet) {
            return $this->json(['error' => 'Paramètres manquants'], 400);
        }

        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->json(['error' => 'Aucun nouveau fichier envoyé'], 400);
        }

        try {
            $newFileName = $uploadedFile->getClientOriginalName();
            $uploadedFile->move(
                $this->getParameter('uploads_directory'),
                $newFileName
            );
        } catch (FileException $e) {
            return $this->json(['error' => 'Erreur lors du téléchargement du nouveau fichier'], 500);
        }

        $upload->setFileName($newFileName);
        $upload->setFilePath('/uploads/' . $newFileName);
        $upload->setProjetPreuve($this->entityManager->getReference(ProjetPreuve::class, $idPreuveProjet)); // Mettre à jour l'ID de la preuve du projet
        
        $this->entityManager->flush();

        return $this->json(['message' => 'Fichier uploadé mis à jour avec succès'], 200);
    }
    #[Route('/api/uploads', name: 'api_uploads_', methods: ['GET'])]
    public function getAllUploads(UploadFileRepository $uploadFileRepository): Response
    {
        $uploads = $uploadFileRepository->findBy([], ['projetPreuve' => 'ASC']);
    
        $uploadsArray = [];
        foreach ($uploads as $upload) {
            $uploadsArray[] = [
                'id' => $upload->getId(),
                'fileName' => $upload->getFileName(),
                'filePath' => $upload->getFilePath(),
                'projetPreuveId' => $upload->getProjetPreuve() ? $upload->getProjetPreuve()->getProjetPreuveLibelle() : null
            ];
        }
    
        return $this->json($uploadsArray, 200);
    }

    #[Route('/api/upload/{id}', name: 'api_upload_show', methods: ['GET'])]
    public function getUploadById(int $id): Response
    {
        $upload = $this->uploadFileRepository->find($id);

        if (!$upload) {
            return $this->json(['error' => 'Fichier uploadé non trouvé'], 404);
        }

        $uploadArray = [
            'id' => $upload->getId(),
            'fileName' => $upload->getFileName(),
            'filePath' => $upload->getFilePath(),
            'projetPreuveId' => $upload->getProjetPreuve()->getProjetPreuveLibelle()
        ];

        return $this->json($uploadArray, 200);
    }
    #[Route('/api/upload/{id}', name: 'api_upload_delete', methods: ['DELETE'])]
public function deleteUpload(int $id): Response
{
    $upload = $this->uploadFileRepository->find($id);

    if (!$upload) {
        return $this->json(['error' => 'Fichier uploadé non trouvé'], 404);
    }

    // Supprimer le fichier physique s'il existe
    $filePath = $upload->getFilePath();
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Supprimer l'entité de la base de données
    $this->entityManager->remove($upload);
    $this->entityManager->flush();

    return $this->json(['message' => 'Fichier uploadé supprimé avec succès'], 200);
}
}
