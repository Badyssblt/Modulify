<?php

// src/Service/FirebaseService.php

namespace App\Service;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Storage;

class FirebaseService
{
    private $storage;

    public function __construct()
    {
        // Initialisation de Firebase Storage via Factory
        $factory = (new Factory)->withServiceAccount(__DIR__.'/../../modulify.json');
        $this->storage = $factory->createStorage();
    }

    public function getFile(string $path): string
    {
        $bucket = $this->storage->getBucket();

        $object = $bucket->object($path);

        $tempFile = tempnam(sys_get_temp_dir(), 'firebase_');
        $object->downloadToFile($tempFile);

        return $tempFile;
    }

    public function uploadFile($file): string
    {
        $bucket = $this->storage->getBucket();
        $filePath = $file->getClientOriginalName();
        $bucket->upload(
            fopen($file->getPathname(), 'r'),
            [
                'name' => $filePath
            ]
        );

        return $filePath;
    }

    public function deleteFile($file): string
    {
        $bucket = $this->storage->getBucket();
        $object = $bucket->object($file);

        if($object->exists()){
            $object->delete();
            return true;
        }

        return false;
    }
}
