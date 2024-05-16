<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Projet;
use App\Repository\ProjetRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RapportController extends AbstractController
{
    private $projetRepository;

    public function __construct(ProjetRepository $projetRepository)
    {
        $this->projetRepository = $projetRepository;
    }
    #[Route('/images/{imageName}', name: 'app_rapport_id', requirements: ['id' => '\d+'])]
    public function serveImageAction(string $imageName): Response
    {
        // Construisez le chemin absolu vers l'image
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/images/' . $imageName;

        // Vérifiez si le fichier existe
        if (!file_exists($imagePath) || !is_readable($imagePath)) {
            throw $this->createNotFoundException('L\'image demandée n\'existe pas.');
        }

        // Renvoyez la réponse avec le contenu de l'image
        return new Response(file_get_contents($imagePath), 200, [
            'Content-Type' => 'image/jpeg', // Remplacez par le type MIME approprié de votre image
        ]);
    }

    #[Route('/rapport/{id}', name: 'app_rapport_id', requirements: ['id' => '\d+'])]
    public function index(Request $request, int $id): Response
    {
        // Recherche du projet par son ID dans le repository
        $projet = $this->projetRepository->find($id);

        // Vérifie si le projet existe
        if (!$projet) {
            throw $this->createNotFoundException('Le projet avec l\'ID ' . $id . ' n\'existe pas.');
        }
      
        $html = '<h1>Rapport du projet '. $projet->getProjetLibelle() . '</h1> ';
$html .= '<p>Catégorie du projet: ' . ($projet->getCategorie() ? $projet->getCategorie()->getCategorieNom() : '') . '</p>';
$html .= '<p>Client : ' . ($projet->getClient() ? $projet->getClient()->getPersonneContact() : '') . '</p>';
$html .= '<p>Description du projet: ' . $projet->getProjetDescription() . '</p>';
$html .= '<p>Référence du projet: ' . $projet->getProjetReference() . '</p>';
$html .= '<p>Date de démarrage: ' . $projet->getProjetDateDemarrage()->format('Y-m-d') . '</p>';
$html .= '<p>Date d\'achèvement: ' . $projet->getProjetDateAchevement()->format('Y-m-d') . '</p>';
$html .= '<p>URL fonctionnel: ' . $projet->getProjetUrlFonctionnel() . '</p>';
$html .= '<p>Description des services effectivement rendus: ' . $projet->getProjetDescriptionServiceEffectivementRendus() . '</p>';
$html .= '<p>Lieu du projet: ' . ($projet->getLieu() ? $projet->getLieu()->getLieuNom() : '') . '</p>';

// Ajoutez la balise <img> avec l'URL correcte vers votre image


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
    'Content-Disposition' => 'attachment; filename="projet_' . $id . '.pdf"'
));
    }


   
}