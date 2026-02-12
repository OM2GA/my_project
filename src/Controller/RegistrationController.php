<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Person;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $address = new Address();
        $address->setStreet($data['address']['street'] ?? 'Rue inconnue');
        $address->setZipcode($data['address']['zipcode'] ?? '00000');
        $address->setCity($data['address']['city'] ?? 'Ville inconnue');

        $person = new Person();
        $person->setFirstname($data['firstname'] ?? 'John');
        $person->setLastname($data['lastname'] ?? 'Doe');
        $person->setBirthdate(new \DateTime($data['birthdate'] ?? '2000-01-01'));
        $person->setAddress($address); 

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $data['password']
            )
        );
        
        $user->setPerson($person);

        $entityManager->persist($person); 
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }
}