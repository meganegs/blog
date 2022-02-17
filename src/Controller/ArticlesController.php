<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\Article1Type;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ArticlesController extends AbstractController
{
    /**
     * @Route("/{id}", name="article_index")
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findLastArticles(),
        ]);
    }
    
    /**
     * @Route("/new/{id}", name="article_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articles = new Articles();
        $form = $this->createForm(Article1Type::class, $articles);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($articles);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('articles/new.html.twig', [
            'articles' => $articles,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="article_show")
     */
    
    public function show(Articles $articles): Response
    {
        return $this->render('articles/show.html.twig', [
            'articles' => $articles,
        ]);
    }
    
    /**
     * @Route("/{id}/edit", name="article_edit")
     */
    public function edit(Request $request, Articles $articles, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Article1Type::class, $articles);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('articles/edit.html.twig', [
            'articles' => $articles,
            'form' => $form,
        ]);
    }

    
    /**
      * @Route("/{id}", name="article_delete")
      */
    public function delete(Request $request, Articles $articles, EntityManagerInterface $entityManager): Response
    {
         if ($this->isCsrfTokenValid('delete'.$articles->getId(), $request->request->get('_token'))) {
            $entityManager->remove($articles);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}
