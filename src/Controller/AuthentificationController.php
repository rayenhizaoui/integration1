<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{

    /*    #[Route('/login1', name: 'app_login1')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirigez-le vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_show_users');
        }

        // Récupère une éventuelle erreur de connexion de la dernière tentative de connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi par l'utilisateur (s'il y en a un)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,]);
    }
*/
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // Cette méthode ne sera jamais exécutée, car la route de déconnexion est gérée par le système de sécurité
    }


    #[Route('/register1', name: 'app_register1')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Crée une nouvelle instance de l'entité User
        $user = new User();

        // Crée un formulaire en utilisant le UserType
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du mot de passe avant de le stocker
            /*    $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );*/
            $user->setPassword($form->get('password')->getData());

            // Définition du rôle de l'utilisateur (vous pouvez le modifier selon vos besoins)
            $user->setRole('ROLE_USER');

            // Enregistrement de l'utilisateur en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection vers la page de connexion après l'inscription
            return $this->redirectToRoute('app_login1');
        }

        return $this->render('authentification/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
