<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;


class AccueilController extends AbstractController
{



    /**
     * @Route ("/accueil", name="accueil")
     */
    public function showYears(EntityManagerInterface $entityManager) : Response
    {
        $repository = $entityManager->getRepository(Articles::class);
        $articles = $repository->showYear();

        return $this->render('accueil/index.html.twig', [
            'articles' => $articles
        ]);
    }

}
