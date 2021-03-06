<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{

    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function adminHome(ArticleRepository $articleRepository, StoreRepository $storeRepository)
    {
        $articles = $articleRepository->findBy([],['id' => 'DESC'], 3);
        $stores = $storeRepository->findBy([],['id' => 'DESC'], 3);

        return $this->render("Admin/home.html.twig", ["articles" => $articles, 'stores' => $stores]);
    }

    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function adminArticles(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->render("Admin/articles.html.twig", ["articles" => $articles]);
    }

    /**
     * @Route("/admin/boutique", name="admin_boutique")
     */
    public function adminBoutique(StoreRepository $storeRepository)
    {
        $stores = $storeRepository->findAll();
        return $this->render("Admin/boutique.html.twig", ["stores" => $stores]);
    }
}