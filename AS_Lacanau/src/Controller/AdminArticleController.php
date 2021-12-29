<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminArticleController extends AbstractController
{


    /**
     * @Route("/admin/article/create", name="admin_article_create")
     */
    //je crées une fonction pour enregistrer un nouveau article.
    //je demande à symfony de instancier un objet de la classe Request,
    // car elle va Récupére et contenir les données POST du form.
    //je demande à symfony de instancier un objet de la classe EntityManagerInterface,
    // pour enregistrer mon instance artucle dans ma BDD(symfony se charge de créer la requete sql a envoyer vers MySQL).
    public function createArticle(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger)
    {
        //je instancier un objet de class article
        $article = new article();

        //Ensuite je utilise la méthode createForm() de la classe
        // AbstractController dont notre contrôleur hérite pour créer le formulaire.
        // En premier paramètre, j'envie le chemin de la class BookTyps sur la quel le formulaire est basé,
        // puis en deuxième parametre  l'instance book qui va contenir les données.
        //symfony fait la connexion entre le formulaire et l'intance book.
        $form = $this->createForm(ArticleType::class, $article);


        // Asssocier le formulaire à la classe Request (le formulaire
        // lui est associé à l'instance de l'entité Book)
        //donc je recupere les info dans mon instance request et je les transfert dans mon instance form.
        $form->handleRequest($request);

        // Vérifier que le formulaire a été envoyé
        // le isValid empeche que des données invalides par rapports aux types de colonnes
        // soient insérées + prévient les injections SQL
        if ($form->isSubmitted() && $form->isValid())
        {
            // gestion de l'upload d'image
            // 1) récupérer le fichier uploadé
            $coverFile = $form->get('coverFilenam')->getData();

            if ($coverFile) {
                // 2) récupérer le nom du fichier uploadé
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);

                // 3) renommer le fichier avec un nom unique
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                // 4) déplacer le fichier dans le dossier publique
                $coverFile->move(
                    $this->getParameter('cover_directory'),
                    $newFilename
                );

                // 5) enregistrer le nom du fichier dans la colonne coverFilename
                $article->setCoverFilenam($newFilename);
            }

            //puis J'enregisttre l'instance de la classe Article (l'entité) en BDD avec les methode
            // de la class EntityManagerInterface.
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', "L'article a bien été enregistré !");
            return $this->redirectToRoute('admin_home');
        }




        return $this->render("Admin/article_create.html.twig", ['form' => $form->createView()]);

    }

    /**
     * @Route("/admin/article/update/{id}", name="admin_article_update")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        // j'utilise la méthode find de la classe BookRepository
        // pour récupérer un livre de la table book avec $id recupere de l'url.
        $Article = $articleRepository->find($id);
        $form = $this->createForm(ArticleType::class, $Article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // gestion de l'upload d'image
            // 1) récupérer le fichier uploadé
            $coverFile = $form->get('coverFilenam')->getData();

            if ($coverFile) {
                // 2) récupérer le nom du fichier uploadé
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);

                // 3) renommer le fichier avec un nom unique
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                // 4) déplacer le fichier dans le dossier publique
                $coverFile->move(
                    $this->getParameter('cover_directory'),
                    $newFilename
                );

                // 5) enregistrer le nom du fichier dans la colonne coverFilename
                $Article->setCoverFilenam($newFilename);
            }
            //puis J'enregisttre l'instance de la classe Article (l'entité) en BDD avec les methode
            // de la class EntityManagerInterface.
            $entityManager->persist($Article);
            $entityManager->flush();

            $this->addFlash('success', "L'article a bien été modifier!");
            return $this->redirectToRoute('admin_home');

        }

        return $this->render("admin/article_update.html.twig", ['form' => $form->createView(), 'article' => $Article]);

    }

    /**
     * @Route("/admin/article/delete/{id}", name="admin_article_delete")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        // j'utilise la méthode find de la classe ArticleRepository
        // pour récupérer un livre de la table book avec $id recupere de l'url.
        $article = $articleRepository->find($id);

        //je utilise les methode remove() et flush pour préparer et executer
        //la suppression de l'instance de la classe Book (l'entité) en BDD.
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', "L'article a bien été supprimer !");
        return $this->redirectToRoute("admin_home");

    }
}