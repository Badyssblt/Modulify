<?php

namespace App\Controller;

use App\Repository\AssetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/assets', name: 'app_admin_assets')]
    public function index(AssetRepository $assetRepository): JsonResponse
    {
        return $this->json($assetRepository->findAll(), Response::HTTP_OK, [], ['groups' => ['collection:admin:asset']]);
    }
}
