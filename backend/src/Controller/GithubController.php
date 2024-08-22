<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GithubController extends AbstractController
{
    #[Route('/auth/github', name: "app_github_connect")]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // Redirect to GitHub for authentication
        return $clientRegistry->getClient('github')->redirect(['user:email']);
    }

    #[Route('/auth/github/check', name: "github_check")]
public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
{
    try {
        $client = $clientRegistry->getClient('github');

        // Récupère le token d'accès
        $accessToken = $client->getAccessToken();

        // Récupère les informations de l'utilisateur
        $user = $client->fetchUserFromToken($accessToken);

        // Stocke les informations dans la session
        $session = $request->getSession();
        $session->set('github_user', $user->toArray());
        $session->set('github_token', $accessToken->getToken());

        // Redirige vers la page d'accueil ou autre
        return $this->json(['message' => 'Connexion réussi', 'token' => $accessToken]);

    } catch (\Exception $e) {
        // Gérer les erreurs
        return new JsonResponse(['error' => 'Erreur : ' . $e->getMessage()], 400);
    }
}
}
