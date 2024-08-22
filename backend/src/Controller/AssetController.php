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

        dd($assetArray);
                        
    }

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


    #[Route('/asset/{id}', name: 'app_asset_delete', methods: ['DELETE'])]
    public function delete(Asset $asset, EntityManagerInterface $manager)
    {
        $manager->remove($asset);
        $manager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

}
