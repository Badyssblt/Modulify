<?php


namespace App\Controller;

use App\Service\FirebaseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileController extends AbstractController
{

    #[Route('/download/{filename}', name: "file_download")]
    public function download(string $filename, FirebaseService $firebaseService): Response
    {
        try {
            $filePath = $filename;

            $tempFile = $firebaseService->getFile($filePath);

            return $this->file($tempFile, $filename, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        } catch (\Exception $e) {
            return new Response("Error: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
