<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PersonController extends AbstractController
{
    #[Route('/person/add', name: 'app_person_add', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $person = new Person();
        $person->setFirstname($data['firstname'] ?? null);
        $person->setLastname($data['lastname'] ?? null);
        $person->setEmail($data['email'] ?? null);
        $person->setBirthdate(new \DateTime($data['birthdate']));

        $address = new Address();
        $address->setStreet($data['address']['street']);
        $address->setZipcode($data['address']['zipcode']);
        $address->setCity($data['address']['city']);

        $person->setAddress($address);

        $errors = $validator->validate($person);
        if (count($errors) > 0) {
            $listErrors = [];
            foreach ($errors as $error) {
                $listErrors[] = $error->getPropertyPath() . ' : ' . $error->getMessage();
            }
            return new JsonResponse(['errors' => $listErrors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($person);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Person created'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/person/{id}', name: 'app_person_get', methods: ['GET'])]
    public function getPerson(Person $person): JsonResponse
    {
        return new JsonResponse([
            'id' => $person->getId(),
            'firstname' => $person->getFirstname(),
            'lastname' => $person->getLastname(),
            'email' => $person->getEmail(),
            'birthdate' => $person->getBirthdate()->format('Y-m-d'),
            'address' => $person->getAddress() ? [
                'street' => $person->getAddress()->getStreet(),
                'zipcode' => $person->getAddress()->getZipcode(),
                'city' => $person->getAddress()->getCity(),
            ] : null
        ]);
    }
    #[Route('/person/{id}', name: 'app_person_delete', methods: ['DELETE'])]
    public function delete(Person $person, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($person);
        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/person/{id}/articles', name: 'app_person_articles', methods: ['GET'])]
    public function getArticles(Person $person): JsonResponse
    {
        return $this->json($person->getArticles(), 200, [], ['groups' => ['article:read']]);
    }
}