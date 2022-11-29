<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'category_article')]
    public function show($id,EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Category::class);
        $categories = $repository->findAll();

        $repository = $entityManager->getRepository(Category::class);
        $articles = $repository->findArticlesByCategoryId();
        return $this->render('category/show.html.twig', [
            'categories' => $categories
        ]);
    }
}
