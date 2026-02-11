<?php

namespace App\Controller;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonController extends AbstractController
{
    #[Route('/person/add', name: 'app_person_add', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $person = new Person();
        
        $person->setFirstname($data['firstname']);
        $person->setLastname($data['lastname']);
        $person->setEmail($data['email']);
        $person->setBirthdate(new \DateTime($data['birthdate']));

        $entityManager->persist($person);
        
        $entityManager->flush();

        return new JsonResponse(['status' => 'Person created'], JsonResponse::HTTP_CREATED);
    }
}