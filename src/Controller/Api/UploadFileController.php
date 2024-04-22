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
    public function upload(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['file'])) {
            return new Response('Le fichier n\'a pas été fourni.', Response::HTTP_BAD_REQUEST);
        }

        // Décode le contenu du fichier encodé en base64
        $fileContent = base64_decode($data['file']);

       // Crée un fichier temporaire et y écrit le contenu
    $tempFilePath = tempnam(sys_get_temp_dir(), 'uploaded_file_');
    file_put_contents($tempFilePath, $fileContent);


        // Vous pouvez maintenant traiter le fichier comme vous le souhaitez

        return new Response('Fichier uploadé avec succès.', Response::HTTP_OK);
    }}