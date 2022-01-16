<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\PlayersRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeUserController extends AbstractController
{

    /**
     * je créé une route (donc une page)
     * dans une annotation. Je lui associe l'url "/home" qui
     * est la page d'accueil.
     * Ma route va appeler la méthode home, car l'annotation
     * est placée au dessus de la méthode
     * @Route("/user/home", name="user_home")
     */
    public function home(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy([],['id' => 'DESC'], 3);
        return $this->render("User/home.html.twig", ["articles" => $articles]);
    }

    /**
     * @Route("/user/articles", name="user_articles")
     */
    public function articles(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->render("User/articles.html.twig", ["articles" => $articles]);
    }

    /**
     * @Route("/user/article/{id}", name="user_article")
     */
    public function article($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        return $this->render("User/article.html.twig", ["article" => $article]);
    }

    /**
     * @Route("/user/seniors", name="user_seniors")
     */
    public function seniors(PlayersRepository $playersRepository)
    {
        $players = $playersRepository->findAll();
        return $this->render("User/seniors.html.twig", ["players" => $players]);
    }


    /**
     * @Route("/user/search", name="user_search_articles")
     */
    //je demande à symfony de instancier un objet de la classe Request, et la class BookRepository.
    public function searchArticles(ArticleRepository $articleRepository, Request $request)
    {
        // je récupère ce que tu l'utilisateur a recherché grâce à la classe Request
        $search = $request->query->get('search');


        // je fais ma requête en BDD grâce à la méthode que j'ai créée searchByTitle
        $articles = $articleRepository->searchByTitle($search);

        return $this->render("User/articles_search.html.twig", ['articles' => $articles]);

    }

    /**
     * @Route("/user/classement", name="user_classement")
     */
    public function classement()
    {
        return $this->render("User/classement.html.twig");
    }

    /**
     * @Route("/user/resultats", name="user_resultats")
     */
    public function resultats()
    {
        return $this->render("User/resultats.html.twig");
    }

    /**
     * @Route("/user/calendrier", name="user_calendrier")
     */
    public function calendrier()
    {
        return $this->render("User/calendrier.html.twig");
    }

    /**
     * @Route("/user/contact", name="user_contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $nom = 'anassmoujahid@hotmail.fr';
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to($nom)
                ->subject($contact->get('subject')->getData())
                ->htmlTemplate('User/mail.html.twig')
                ->context([
                    'objet' => $contact->get('subject')->getData(),
                    'mail' => $contact->get('email')->getData(),
                    'nom' => $contact->get('nom')->getData(),
                    'message' => $contact->get('message')->getData(),
                ]);

            $mailer->send($email);

            $this->addFlash('message', 'votre email a bien été envoyé ');
            return $this->redirectToRoute('user_home');

        }

        return $this->render("User/contact.html.twig", ['form' => $form->createView()]);

    }
}
