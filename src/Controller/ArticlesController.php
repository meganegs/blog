<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;

    }
  

    /**
     * @Route("/", name="articles_index")
     */
   

    public function index(ArticlesRepository $articlesRepository)
    {

        $lastArticles = $articlesRepository->findLastArticles(5);
        //dd($lastArticles);
        return $this->render('articles/index.html.twig', [
            'lastArticles' => $lastArticles,
        ]);
    }

    /**
     * Affichage de l'article
     * @Route("articles/{id}", name="articles_read")
     * @param Articles $articles
     * @return Response
     */
    public function read(Articles $articles):Response
    {
        return $this->render('articles/read.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * Editer un article
     * @Route("articles/{id}/new", name="articles_new")
     * @return Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $articles = new Articles();
        $form = $this->createForm(ArticleType::class, $articles);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //Methode deprecie to symfony 6
            /*$manager = $this->getDoctrine()->getManager();

            $manager->persist($articles);
            $manager->flush();*/

            return $this->redirectToRoute('articles_index');
        }
        return $this->render('articles/news.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * Editer un article
     * @Route("articles/{id}/edit", name="articles_edit")
     * @return Articles $articles
     * @return Request $request
     * @return Response
     */
    public function edit(ManagerRegistry $doctrine, Request $request): Response
    {

        $em = $doctrine->getManager();

        $articles = new Articles();
        $form = $this->createForm(ArticleType::class, $articles);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($articles);
            $em->flush();

           // return $this->redirectToRoute('articles_index');
        }       
        
        return $this->render('articles/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

    
    /**
     * Delete
     * @Route("/articles/{id}/delete", name="articles_delete")
     * @return  Articles $articles
     * @return RedirectResponse
     *
     */
    public function delete(ManagerRegistry $doctrine): RedirectResponse
    {
        
        $em = $doctrine()->getManager();
        
        $articles = new Articles();
        //$articles = $articles->find($id);
        /*if(!$articles){
            throw $this->createNotFoundException('...'. $id
        );
        }*/

        $em->remove($articles);
        $em->flush();

        return $this->redirectToRoute('articles_index');
    }


}
