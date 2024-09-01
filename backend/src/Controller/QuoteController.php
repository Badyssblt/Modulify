<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class QuoteController extends AbstractController
{


    #[Route('/quote', name: 'app_quote')]
    public function index(HttpClientInterface $httpClient): JsonResponse
    {
        // Remplacez cette URL par l'URL de votre API tierce
        $apiUrl = 'https://zenquotes.io/api/random';

        try {
            // Envoyer une requête GET à l'API
            $response = $httpClient->request('GET', $apiUrl);

            // Vérifiez si la requête a réussi (code de statut 200-299)
            if ($response->getStatusCode() === 200) {
                // Décoder la réponse JSON
                $data = $response->toArray();

                // Retourner la réponse JSON
                return new JsonResponse($data);
            } else {
                // Gérer le cas où l'API retourne un code de statut d'erreur
                return new JsonResponse(['error' => 'Failed to fetch quote'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            // Gérer les exceptions (par exemple, erreurs réseau ou d'API)
            return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
