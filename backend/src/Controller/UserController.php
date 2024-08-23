<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[IsGranted('ROLE_USER', message: 'Veuillez vous connectez', statusCode: 401)]
    #[Route('/user/me', name: 'app_user_me')]
    public function me()
    {
        $user = $this->getUser();

        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['item:user']]);
    }

    #[Route('/user', name: 'app_user_edit', methods: ['PATCH'])]
    public function update(EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserRepository $userRepository)
    {
        $userItem = $serializer->deserialize($request->getContent(), User::class, 'json');
    
        $currentUser = $this->getUser();
    
        if ($userItem->getEmail() !== $currentUser->getEmail()) {
            $existingUser = $userRepository->findOneBy(['email' => $userItem->getEmail()]);

            if ($existingUser !== null) {
                return $this->json(['error' => 'Cette email est déjà utilisé'], Response::HTTP_BAD_REQUEST);
            }
        }

        if($userItem->getEmail()){
            $currentUser->setEmail($userItem->getEmail());
        }

        if($userItem->getName()){
            $currentUser->setName($userItem->getName());
        }
        
        if($userItem->getPassword() !== null){
            $password = $hasher->hashPassword($currentUser, $userItem->getPassword());
            $currentUser->setPassword($password);
        }
        
        $manager->persist($currentUser);
        $manager->flush();

        return $this->json($currentUser, Response::HTTP_OK, [], ['groups' => ['item:user']]);
            
    }
}
