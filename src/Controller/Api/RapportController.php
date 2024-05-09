<?php

namespace App\Controller\Api;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

class RapportController extends AbstractController
{
    
    private $projetRepository;

    public function __construct(ProjetRepository $projetRepository)
    {
        $this->projetRepository = $projetRepository;
    }


    #[Route('/rapport/{nom}', name: 'app_rapport_nom')]
    public function index(string $nom): Response
    {
        // Recherche du projet par son nom dans le repository
        $projet = $this->projetRepository->findOneBy(['projetLibelle' => $nom]);

        // Vérifie si le projet existe
        if (!$projet) {
            throw $this->createNotFoundException('Le projet avec le nom ' . $nom . ' n\'existe pas.');
        }

        // Formatage des données du projet pour inclusion dans le PDF
        $html = '<h1>Rapport</h1>';
        $html .= '<p>Nom du projet: ' . $projet->getProjetLibelle() . '</p>';
        // Ajoutez d'autres champs de projet selon votre besoin

        // Générer le PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        // Retourner le PDF comme réponse HTTP
        return new Response($output, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="projet_' . $nom . '.pdf"'
        ));
    }
}