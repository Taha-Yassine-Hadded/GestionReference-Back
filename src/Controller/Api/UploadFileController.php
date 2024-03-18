<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadFileController extends AbstractController
{
    #[Route('/upload/file', name: 'app_upload_file')]
    public function index(): Response
    {
        return $this->render('upload_file/index.html.twig', [
            'controller_name' => 'UploadFileController',
        ]);
    }
}
