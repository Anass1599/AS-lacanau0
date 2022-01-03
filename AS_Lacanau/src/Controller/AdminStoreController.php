<?php

namespace App\Controller;


use App\Entity\Store;
use App\Form\StoreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminStoreController extends AbstractController
{


    /**
     * @Route("/admin/article/create", name="admin_article_create")
     */
    //je crées une fonction pour enregistrer un nouveau article.
    //je demande à symfony de instancier un objet de la classe Request,
    // car elle va Récupére et contenir les données POST du form.
    //je demande à symfony de instancier un objet de la classe EntityManagerInterface,
    // pour enregistrer mon instance artucle dans ma BDD(symfony se charge de créer la requete sql a envoyer vers MySQL).
    public function createArticle(Request $request, EntityManagerInterface $entityManager)
    {
        //je instancier un objet de class store
        $store= new Store();

        //Ensuite je utilise la méthode createForm() de la classe
        // AbstractController dont notre contrôleur hérite pour créer le formulaire.
        // En premier paramètre, j'envie le chemin de la class BookTyps sur la quel le formulaire est basé,
        // puis en deuxième parametre  l'instance book qui va contenir les données.
        //symfony fait la connexion entre le formulaire et l'intance book.
        $form = $this->createForm(StoreType::class, $store);


        // Asssocier le formulaire à la classe Request (le formulaire
        // lui est associé à l'instance de l'entité Book)
        //donc je recupere les info dans mon instance request et je les transfert dans mon instance form.
        $form->handleRequest($request);

        // Vérifier que le formulaire a été envoyé
        // le isValid empeche que des données invalides par rapports aux types de colonnes
        // soient insérées + prévient les injections SQL
        if ($form->isSubmitted() && $form->isValid())
        {
            //puis J'enregisttre l'instance de la classe Article (l'entité) en BDD avec les methode
            // de la class EntityManagerInterface.
            $entityManager->persist($store);
            $entityManager->flush();

            $this->addFlash('success', "L'article a bien été enregistré !");
            return $this->redirectToRoute('admin_home');
        }




        return $this->render("Admin/store_create.html.twig", ['form' => $form->createView()]);

    }

}