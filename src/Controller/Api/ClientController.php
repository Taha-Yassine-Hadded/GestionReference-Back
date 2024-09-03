<?php

namespace App\Controller\Api;

use App\Entity\Client;
use App\Entity\Pays;
use App\Entity\Projet;
use App\Entity\NatureClient;
use App\Entity\Reference;
use App\Entity\SecteurActivite;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ClientController extends AbstractController
{
    #[Route('/api/getAll/clients', name: 'api_client_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        //$this->checkToken($tokenStorage);

        // Récupérer les clients triés par le nom de la personne de contact
        $clients = $entityManager->getRepository(Client::class)->findBy([], ['clientPersonneContact1' => 'ASC']);

        $data = [];

        foreach ($clients as $client) {
            $data[] = $this->serializeClientNom($client);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/get/client/{id}', name: 'api_client_show', methods: ['GET'])]
    public function show(Client $client, TokenStorageInterface $tokenStorage): JsonResponse
    {
        //$this->checkToken($tokenStorage);
        $data = $this->serializeClient($client);

        return new JsonResponse($data, Response::HTTP_OK);
    }
    #[Route('/api/getOne/client/{id}', name: 'api_client_Nom', methods: ['GET'])]
    public function getClientInfo(Client $client, TokenStorageInterface $tokenStorage): JsonResponse
    {
        //$this->checkToken($tokenStorage);
        $data = $this->serializeClientInfo($client);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/create/clients', name: 'api_client_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        //$this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        $client = new Client();
        $client->setClientRaisonSocial($data['clientRaisonSocial']);
        $client->setClientRaisonSocialShort($data['clientRaisonSocialShort']);
        $client->setClientPersonneContact1($data['clientPersonneContact1']);
        $client->setClientPersonneContact2($data['clientPersonneContact2']);
        $client->setClientPersonneContact3($data['clientPersonneContact3']);
        $client->setClientAdresse($data['clientAdresse']);
        $client->setClientTelephone1($data['clientTelephone1']);
        $client->setClientTelephone2($data['clientTelephone2']);
        $client->setClientTelephone3($data['clientTelephone3']);
        $client->setClientEmail($data['clientEmail']);

        // Récupérer l'objet Pays en fonction de l'ID fourni dans la requête
        $pays = $entityManager->getRepository(Pays::class)->find($data['paysId']);
        // Vérifier si le pays existe
        if (!$pays) {
            return new JsonResponse(['message' => 'Pays non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $client->setPays($pays);

        // Récupérer l'objet Pays en fonction de l'ID fourni dans la requête
        $natureClient = $entityManager->getRepository(NatureClient::class)->find($data['natureClientId']);
        // Vérifier si le pays existe
        if (!$natureClient) {
            return new JsonResponse(['message' => 'Nature client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $client->setNatureClient($natureClient);


        foreach ($data['secteurs'] as $secteurId) {
            $secteur = $entityManager->getRepository(SecteurActivite::class)->find($secteurId);
            if ($secteur) {
                $client->addSecteurActivite($secteur);
            }
        }

        $entityManager->persist($client);
        $entityManager->flush();

        $responseData = $this->serializeClient($client);
        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }
    #[Route('/api/update/client/{id}', name: 'api_client_update', methods: ['PUT'])]
    public function update(Request $request, Client $client, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        //$this->checkToken($tokenStorage);
        $data = json_decode($request->getContent(), true);

        // Mettre à jour les propriétés du client
        $client->setClientRaisonSocial($data['clientRaisonSocial']);
        $client->setClientRaisonSocialShort($data['clientRaisonSocialShort']);
        $client->setClientPersonneContact1($data['clientPersonneContact1']);
        $client->setClientPersonneContact2($data['clientPersonneContact2']);
        $client->setClientPersonneContact3($data['clientPersonneContact3']);
        $client->setClientAdresse($data['clientAdresse']);
        $client->setClientTelephone1($data['clientTelephone1']);
        $client->setClientTelephone2($data['clientTelephone2']);
        $client->setClientTelephone3($data['clientTelephone3']);
        $client->setClientEmail($data['clientEmail']);

        // Récupérer l'objet Pays en fonction de l'ID fourni dans la requête
        $pays = $entityManager->getRepository(Pays::class)->find($data['paysId']);
        // Vérifier si le pays existe
        if (!$pays) {
            return new JsonResponse(['message' => 'Pays non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $client->setPays($pays);

        // Récupérer l'objet Pays en fonction de l'ID fourni dans la requête
        $natureClient = $entityManager->getRepository(NatureClient::class)->find($data['natureClientId']);
        // Vérifier si le pays existe
        if (!$natureClient) {
            return new JsonResponse(['message' => 'Nature client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $client->setNatureClient($natureClient);

        // Retrieve and update the Secteurs d'Activité
        $secteurIds = $data['secteurs']; // Array of sector IDs
        $secteurs = $entityManager->getRepository(SecteurActivite::class)->findBy(['id' => $secteurIds]);

        // Clear existing secteurs and set new ones
        $client->getSecteurActivites()->clear();
        foreach ($secteurs as $secteur) {
            $client->addSecteurActivite($secteur); // Ensure you have this method in your Client entity
        }


        $entityManager->flush();

        $responseData = $this->serializeClient($client);
        return new JsonResponse($responseData, Response::HTTP_OK);
    }

    #[Route('/api/delete/client/{id}', name: 'api_client_delete', methods: ['DELETE'])]
    public function deleteClient(Client $client, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        //$this->checkToken($tokenStorage);

        $references = $entityManager->getRepository(Reference::class)->findBy(['client' => $client]);

        if ($references != null) {
            foreach ($references as $reference) {
                $reference->setClient(null);
                $entityManager->persist($reference);
            }
            $entityManager->flush();
        }

        // Supprimer le client
        $entityManager->remove($client);
        $entityManager->flush();

        return new JsonResponse('Client supprimé avec succès', Response::HTTP_OK);
    }
    /**
     * Serialize Client entity to array.
     */
    public function serializeClient(Client $client): array
    {
        $secteursActivites = $client->getSecteurActivites();
        $secteurs = [];
        foreach ($secteursActivites as $secteurActivite) {
            $secteurs[] = [
                'id' => $secteurActivite->getId(),
            ];
        }

        return [
            'clientId' => $client->getClientId(),
            'natureClientId' => $client->getNatureClient() ? $client->getNatureClient()->getId() : null,
            'paysId' => $client->getPays() ? $client->getPays()->getId() : null,
            'clientRaisonSocial' => $client->getClientRaisonSocial(),
            'clientRaisonSocialShort' => $client->getClientRaisonSocialShort(),
            'clientAdresse' => $client->getClientAdresse(),
            'clientTelephone1' => $client->getClientTelephone1(),
            'clientTelephone2' => $client->getClientTelephone2(),
            'clientTelephone3' => $client->getClientTelephone3(),
            'clientEmail' => $client->getClientEmail(),
            'clientPersonneContact1' => $client->getClientPersonneContact1(),
            'clientPersonneContact2' => $client->getClientPersonneContact2(),
            'clientPersonneContact3' => $client->getClientPersonneContact3(),
            'secteurs' => $secteurs,
        ];
    }

    /**
     * Serialize Client entity to array.
     */
    public function serializeClientInfo(Client $client): array
    {
        $secteursActivites = $client->getSecteurActivites();
        $secteurs = [];
        foreach ($secteursActivites as $secteurActivite) {
            $secteurs[] = [
                'secteur' => $secteurActivite->getSecteurActiviteLibelle(),
            ];
        }

        return [
            'natureClient' => $client->getNatureClient() ? $client->getNatureClient()->getNatureClient() : null,
            'paysClient' => $client->getPays() ? $client->getPays()->getPaysLibelle() : null,
            'clientRaisonSocial' => $client->getClientRaisonSocial(),
            'clientRaisonSocialShort' => $client->getClientRaisonSocialShort(),
            'clientAdresse' => $client->getClientAdresse(),
            'clientTelephone1' => $client->getClientTelephone1(),
            'clientTelephone2' => $client->getClientTelephone2(),
            'clientTelephone3' => $client->getClientTelephone3(),
            'clientEmail' => $client->getClientEmail(),
            'clientPersonneContact1' => $client->getClientPersonneContact1(),
            'clientPersonneContact2' => $client->getClientPersonneContact2(),
            'clientPersonneContact3' => $client->getClientPersonneContact3(),
            'secteurs' => $secteurs,
        ];
    }

    /**
     * Serialize Client entity to array.
     */
    private function serializeClientNom(Client $client): array
    {
        return [
            'clientId' => $client->getClientId(),
            'personneContact' => $client->getClientPersonneContact1(),
            'clientRaisonSociale' => $client->getClientRaisonSocial(),
            'clientAdresse' => $client->getClientAdresse(),
            'clientEmail' => $client->getClientEmail(),
            'natureClient' => $client->getNatureClient() ? $client->getNatureClient()->getNatureClient() : null,
            'paysClient' => $client->getPays() ? $client->getPays()->getPaysLibelle() : null,
            // Ajoutez d'autres attributs de l'entité que vous souhaitez inclure dans la réponse JSON
        ];
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
