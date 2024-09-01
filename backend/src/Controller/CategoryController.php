<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function getAll(CategoryRepository $categoryRepository)
    {
        return $this->json($categoryRepository->findAll(), Response::HTTP_OK, ['groups' => ['collection:category']]);
    }
}
