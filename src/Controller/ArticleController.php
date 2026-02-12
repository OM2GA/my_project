<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/api/articles', name: 'app_api_articles', methods: ['GET'])]
    public function getAll(ArticleRepository $articleRepository): JsonResponse
    {
        $articles = $articleRepository->findAll();

        return $this->json($articles, 200, [], ['groups' => ['article:read']]);
    }

    #[Route('/api/articles/{id}', name: 'app_api_article_delete', methods: ['DELETE'])]
    public function delete(Article $article, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}