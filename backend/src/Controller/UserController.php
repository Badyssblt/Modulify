<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, ValidatorInterface $validator): Response
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        if(!$user->getName() || !$user->getEmail()) return $this->json(['message' => 'Vous devez fournir un email ou nom'], Response::HTTP_BAD_REQUEST);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()][] = $error->getMessage();
            }
            
            return $this->json([
                'message' => 'Validation failed',
                'errors' => $errorsArray
            ], Response::HTTP_BAD_REQUEST);
        }


        $password = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();

        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'item:user']);
    }
}
