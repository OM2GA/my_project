<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/api/articles', name: 'app_api_articles', methods: ['GET'])]
    public function getAll(ArticleRepository $articleRepository): JsonResponse
    {
        $articles = $articleRepository->findAll();

        return $this->json($articles, 200, [], ['groups' => ['article:read']]);
    }
}