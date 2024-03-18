<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeLangueController extends AbstractController
{
    #[Route('/employe/langue', name: 'app_employe_langue')]
    public function index(): Response
    {
        return $this->render('employe_langue/index.html.twig', [
            'controller_name' => 'EmployeLangueController',
        ]);
    }
}
