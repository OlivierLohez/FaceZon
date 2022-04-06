<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Article;

class BaseController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(): Response
    {
        $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        $article = $repoArticle->findAll();
        return $this->render('base/accueil.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/article',name:'article')]
        public function article(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        if($request->isMethod('POST')){

            $form->handleRequest($request);

            if ($form->isSubmitted()&&$form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->addFlash('notice','Article envoyé');
                return $this->redirectToRoute('article');
            }
        }
        return $this->render('base/article.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
