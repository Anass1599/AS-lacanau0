<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
// controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/admin/user/create", name="admin_user_create")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher,
    UserRepository $userRepository)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $mail = $user->getEmail();
            $email = $userRepository->findOneBy(array('email' => $mail));
            if (is_null($email)) {
                $user->setRoles(["ROLE_USER"]);
                //  je vais chercher les informations de password et plus precisement les données
                $plaintextPassword = $form->get('password')->getData();
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);

                // cette classe permet de préparer sa sauvegarde en bdd
                $entityManager->persist($user);

                // cette classe permet de génèrer et éxecuter la requête SQL
                $entityManager->flush();
                $this->addFlash('success', "Vos informations ont bien été enregistré!");
            }
            else {
                $this->addFlash('success', "ERREUR");
            }


        }


        return $this->render("Admin/user.create.html.twig", ['form' => $form->createView()]);
    }


}
