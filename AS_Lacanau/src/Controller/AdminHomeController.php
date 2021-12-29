<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{

    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function adminHome(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->render("Admin/home.html.twig", ["articles" => $articles]);
    }
}