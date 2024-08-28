<?php

namespace App\Controller;

use App\Entity\Asset;
use App\Repository\AssetRepository;
use App\Service\FirebaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AssetController extends AbstractController
{
    #[Route('/assets', name: "app_assets")]
    public function getAll(AssetRepository $assetRepository)
    {
        $assets = $assetRepository->findBy(['is_public' => true]);

        return $this->json($assets, Response::HTTP_OK, [], ['groups' => ['collection:asset']]);
    }

    #[Route('/assets/home', name: 'app_assets_homepage')]
    public function getHomepageAsset(AssetRepository $assetRepository)
    {
        $assetArray = [];

        
        $assetArray['popular'] = $assetRepository->findByLast('total_download');
        $assetArray['created_at'] = $assetRepository->findByLast('created_at');

        return $this->json($assetArray, Response::HTTP_OK, [], ['groups' => ['collection:asset']]);
                        
    }

    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas la permission pour modifier ce contenu')]
    #[Route('/asset/{id}', name: 'app_asset_delete', methods: ['DELETE'])]
    public function delete(Asset $asset, EntityManagerInterface $manager, FirebaseService $firebaseService)
    {
        try {
            $deleted = $firebaseService->deleteFile($asset->getFile());
            if($deleted){
                $manager->remove($asset);
                $manager->flush();    
            }else {
                return $this->json(['message' => 'Une erreur est survenue lors de la suppression du fichier dans FireBase']);
            }
            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/asset/{id}', name: 'app_assets_item')]
    public function get(Asset $asset)
    {
        return $this->json($asset, Response::HTTP_OK, [], ['groups' => ['item:asset']]);
    }

    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas la permission pour modifier ce contenu')]
    #[Route('/asset', name: 'app_asset_create', methods: ['POST'])]
    public function createAsset(Request $request, EntityManagerInterface $manager, FirebaseService $firebaseService)
    {
        $formdata = $request->request->all();
        $image = $request->files->get('image');
        $file = $request->files->get('file');


        $asset = new Asset();

        $asset->setName($formdata['name']);
        $asset->setPrice($formdata['price']);
        $asset->setDescription($formdata['description']);
        $asset->setHow($formdata['how']);
        $asset->setVersion($formdata['version']);
        $asset->setPublic($formdata['visibility']);

        if ($image instanceof UploadedFile) {
            $asset->setImageFile($image);
        }

        if($file instanceof UploadedFile){
            $filePath = $firebaseService->uploadFile($file);
            $asset->setFile($filePath);
        }

        $manager->persist($asset);
        $manager->flush();

        return $this->json($asset, Response::HTTP_CREATED, [], ['groups' => ['item:asset']]);
    }

    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas la permission pour modifier ce contenu')]
    #[Route('/asset/{id}', name: 'app_asset_update', methods: ['PATCH'])]
    public function updateAsset(Request $request, EntityManagerInterface $manager, FirebaseService $firebaseService, Asset $asset): Response 
    {

    if (!$asset) {
        return $this->json(['error' => 'Asset not found'], Response::HTTP_NOT_FOUND);
    }

    $data = json_decode($request->getContent(), true);

    if (isset($data['name'])) {
        $asset->setName($data['name']);
    }
    if (isset($data['price'])) {
        $asset->setPrice($data['price']);
    }
    if (isset($data['description'])) {
        $asset->setDescription($data['description']);
    }

    if ($request->files->has('image')) {
        $image = $request->files->get('image');
        if ($image instanceof UploadedFile) {
            $asset->setImageFile($image);
        }
    }

    if ($request->files->has('file')) {
        $file = $request->files->get('file');
        if ($file instanceof UploadedFile) {
            $filePath = $firebaseService->uploadFile($file);
            $asset->setFile($filePath);
        }
    }

    $manager->persist($asset);
    $manager->flush();

    return $this->json($asset, Response::HTTP_OK, [], ['groups' => ['item:asset']]);
    }





}
