<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AppelOffre;
use App\Repository\AppelOffreRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

class EtatController extends AbstractController
{
    #[Route('/api/etat/appel-offres/{id}', name: 'api_appel_offres_rapport', methods: ['GET'])]
    public function generatePdf(int $id, AppelOffreRepository $appelOffreRepository): Response
    {
        $appelOffre = $appelOffreRepository->find($id);

        if (!$appelOffre) {
            return new JsonResponse(['message' => 'Appel d\'offre non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dateCreationPDF = new DateTime();
        $currentDate = new \DateTime();
        $dateRemise = $appelOffre->getAppelOffreDateRemise();
        $dateRemisePassed = $dateRemise < $currentDate ? 'Oui' : 'Non';
        $participation = $appelOffre->getAppelOffreParticipation() ? 'Oui' : 'Non';

        $html = '
        <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    h1 {
                        color: #358DCC;
                        text-align: center;
                        margin-bottom: 20px;
                        border-bottom: 2px solid #333;
                        padding-bottom: 10px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
            
            <p><strong>Date de création :</strong> ' . $dateCreationPDF->format('Y-m-d H:i:s') . '</p>
            <p><strong>date de remise passée :</strong> ' . $dateRemisePassed .'</p>
            
    
                <h1>État de l\'appel d\'offre</h1>
                <table>
                   
                    <tr>
                        <th>Devis</th>
                        <td>' . $appelOffre->getAppelOffreDevis() . '</td>
                    </tr>
                    <tr>
                        <th>Objet</th>
                        <td>' . $appelOffre->getAppelOffreObjet() . '</td>
                    </tr>
                    <tr>
                        <th>Date de remise</th>
                        <td>' . $dateRemise->format('Y-m-d') . '</td>
                    </tr>
                    <tr>
                        <th>Retiré</th>
                        <td>' . ($appelOffre->getAppelOffreRetire() ? 'Oui' : 'Non') . '</td>
                    </tr>
                    <tr>
                        <th>Participation</th>
                        <td>' . $participation . '</td>
                    </tr>
                    <tr>
                        <th>État</th>
                        <td>' . ($appelOffre->getAppelOffreEtat() ? 'Oui' : 'Non') . '</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>' . $appelOffre->getAppelOffreType()->getAppelOffreType() . '</td>
                    </tr>
                    <tr>
                        <th>Moyen de livraison</th>
                        <td>' . $appelOffre->getMoyenLivraison()->getMoyenLivraison() . '</td>
                    </tr>
                    <tr>
                        <th>Organisme demandeur</th>
                        <td>' . $appelOffre->getOrganismeDemandeur()->getOrganismeDemandeurLibelle() . '</td>
                    </tr>
                    <tr>
                        <th>Pays</th>
                        <td>' . $appelOffre->getPays()->getPaysNom() . '</td>
                    </tr>
                </table>
            </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="appel_offre_' . $id . '.pdf"'
        ]);
    }
}
