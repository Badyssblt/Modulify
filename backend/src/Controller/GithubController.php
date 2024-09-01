<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GithubController extends AbstractController
{
    #[Route('/auth/github', name: "app_github_connect")]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // Redirect to GitHub for authentication
        return $clientRegistry->getClient('github')->redirect(['user:email', 'repo', 'read:org']);
    }

    #[Route('/auth/github/check', name: "github_check")]
    public function connectCheckAction(UserPasswordHasherInterface $hasher, JWTTokenManagerInterface $jwtManager, Request $request, ClientRegistry $clientRegistry, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        try {
            $client = $clientRegistry->getClient('github');

            $accessToken = $client->getAccessToken();

            $user = $client->fetchUserFromToken($accessToken);

            $email = $user->getEmail();
            $username = $user->getNickname();
            $profileImageUrl = $user->toArray()['avatar_url'];

            $existingUser = $userRepository->findOneBy(['email' => $email]);

            // $response = new RedirectResponse('https://modulify.badyssblilita.fr/auth/github?success=true');
            $response = new RedirectResponse('http://localhost:3000/auth/github?success=true');


            if (!$existingUser) {
                $newUser = new User();
                $defaultPassword = Uuid::v4()->toBase58();
                $password = $hasher->hashPassword($newUser, $defaultPassword);
                $newUser->setPassword($password);
                $newUser->setEmail($email);
                $newUser->setName($username);
                $newUser->setGithubToken($accessToken->getToken());
                $manager->persist($newUser);
                $manager->flush();
            } else {
                $existingUser->setGithubToken($accessToken->getToken());
                $manager->persist($existingUser);
                $manager->flush();
            }

            $jwt = $jwtManager->create($existingUser);
            $response->headers->setCookie(new Cookie('token', $jwt, 0, '/', null, false, false, false, 'Strict'));
            $response->headers->setCookie(new Cookie('github_token', $accessToken->getToken(), 0, '/', null, false, false, false, 'Strict'));
            $response->headers->setCookie(new Cookie('github_email', $email, 0, '/', null, false, false, false, 'Strict'));
            $response->headers->setCookie(new Cookie('github_username', $username, 0, '/', null, false, false, false, 'Strict'));
            $response->headers->setCookie(new Cookie('github_profile_image', $profileImageUrl, 0, '/', null, false, false, false, 'Strict'));


            return $response;
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur : ' . $e->getMessage()], 400);
        }
    }
}
