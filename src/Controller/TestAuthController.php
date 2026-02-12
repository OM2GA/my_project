<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/protected')]
class TestAuthController extends AbstractController
{
    #[Route('/test', name: 'app_api_protected_test', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Bravo ! Vous avez accédé à une route protégée.',
            'user' => $this->getUser()->getUserIdentifier() 
        ]);
    }
}