<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\ArticlesType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Component\HttpFoundation\Request;


class ArticlesController extends AbstractController
{
    #[Route('/articles/create/1', name: 'createArticle')]
    public function createArticle(EntityManagerInterface $entityManager): Response
    {

        $article1 = new Articles();
        $article1->setTitre('Parapluie magique');
        $article1->setDescription('très sec');
        $article1->setDateCreation(new \DateTime("2018/09/18 00:00"));
        $entityManager->persist($article1);

        $article2 = new Articles();
        $article2->setTitre('Super rasoir');
        $article2->setDescription('rase super bien');
        $article2->setDateCreation(new \DateTime("2015/06/12 00:00"));
        $entityManager->persist($article2);

        $article3 = new Articles();
        $article3->setTitre('Baguette normale');
        $article3->setDescription('sympa');
        $article3->setDateCreation(new \DateTime("2021/01/10 00:00"));
        $entityManager->persist($article3);

        $entityManager->flush();

        return $this->render('articles/create.html.twig', [
            'article' => $article1
        ]);
    }

    /**
     * @Route ("/articles", name="afficherArticle")
     */
    public function afficher(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Articles::class);
        $articles = $repository->findAll();

        $repository = $entityManager->getRepository(Category::class);
        $categories = $repository->findAll();

        return $this->render('articles/articles.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

    /**
     * @Route ("/articles/categories/{id}", name="articlesByCateg")
     */
    public function afficherArticlesByCateg($id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Category::class);
        $categories = $repository->findById($id);

        $repository = $entityManager->getRepository(Articles::class);
        $articles = $repository->findByCategoryId($id);


        return $this->render('articles/categ_articles.html.twig', [
            'categories' => $categories,
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/articles/afficher/{id}", name="showOne")
     */
    public function showOne(Articles $article): Response
    {

        $category = $article->getCategory();
        return $this->render('articles/afficher.html.twig', [
            'articles' => $article,
            'category' => $category
        ]);


    }

    /**
     * @Route ("/articles/afficher_annee/{year}", name="findByYear")
     */
    public function findByYear($year, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Articles::class);
        $articles = $repository->findByYear($year);

        return $this->render('articles/afficher_annee.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route ("/articles/magic_articles", name="findContent")
     */
    public function findContent(EntityManagerInterface $entityManager): Response
    {
        $content = "magique";
        $repository = $entityManager->getRepository(Articles::class);
        $articles = $repository->findByContent($content);

        return $this->render('articles/magic_articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route ("/articles/afficher/{id}/voter", name="article_vote", methods="POST")
     */
    public function articleVote(Articles $articles, Request $request, EntityManagerInterface $entityManager)
    {

        $direction = $request->request->get('direction');
        if ($direction === 'up') {
             $articles->setVotes($articles->getVotes() + 1);
            //$articles->upVote();
        } elseif ($direction === 'down') {
             $articles->setVotes($articles->getVotes() - 1);
            // $articles->downVote();
        }
        $entityManager->flush();

        // affichera votre URL article/voter
        /*return $this->render('articles/afficher.html.twig', [
            'articles' => $articles,
            ]);*/

        // redirige vers la route d’affichage d’un article
        return $this->redirectToRoute('showOne', [
            'id' => $articles->getId()
        ]);
    }

    /**
     * @Route("/articles/add", name="addForm")
     */
    public function add(Request $request){
        $article = new Articles();
        $article->setDateCreation(new DateTime());
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //hold les valeurs
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('showOne', [
                'id' => $article->getId()
            ]);

        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
