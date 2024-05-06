<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class SecurityController extends AbstractController
{


    /*    #[Route(path: '/login1', name: 'app_login')]
    public function login(Request $request, UserRepository $userRepository, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Vérifier si le formulaire est soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');

            // Récupérer l'utilisateur à partir de la base de données
            $user = $userRepository->findOneBy(['username' => $username]);

            // Vérifier si l'utilisateur existe et si le mot de passe est valide

            if ($user && $user->getPassword() === $password) {
                // Rediriger l'utilisateur vers une autre page après l'authentification réussie
                return $this->redirectToRoute('app_show_users');
            } else {
                // Afficher un message d'erreur
                $this->addFlash('error', 'Identifiants invalides');
            }
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
*/


    #[Route(path: '/login1', name: 'app_login')]
    public function login(Request $request, UserRepository $userRepository, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Vérifier si le formulaire est soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');

            // Récupérer l'utilisateur à partir de la base de données
            $user = $userRepository->findOneBy(['username' => $username]);

            // Vérifier si l'utilisateur existe et si le mot de passe est valide
            if ($user && $user->getPassword() === $password) {
                // Vérifier le rôle de l'utilisateur
                if ($user->getRole() === 'SPECTATEUR') {
                    return $this->redirectToRoute('app_billet_add');
                } elseif ($user->getRole() === 'ADMIN') {
                    return $this->redirectToRoute('app_show_users');
                }
            } else {
                // Afficher un message d'erreur
                //$this->addFlash('error', 'Identifiants invalides');
                //$this->addFlash('error', $error->getMessage());

                // Récupérer l'erreur d'authentification
                $error = $authenticationUtils->getLastAuthenticationError();

                // Vérifier si une erreur existe
                if ($error !== null) {
                    // Afficher un message d'erreur
                    $this->addFlash('error', $error->getMessage());
                } else {
                    // Afficher un message d'erreur générique
                    $this->addFlash('error', 'Identifiants invalides');
                }
            }
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /*    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
*/

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    public function onLogoutSuccess(): Response
    {
        return new RedirectResponse($this->generateUrl('app_login'));
    }

    #[Route(path: '/show', name: 'show_page')]
    public function showPage(): Response
    {
        // Affiche la page show.html.twig
        return $this->render('user/show.html.twig');
    }



    /*    #[Route('/showUsers', name: 'app_show_users')]
    public function showUsers(UserRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('user/show.html.twig', [
            'users' => $users

        ]);
    }*/

    #[Route('/showUsers/{page}', name: 'app_show_users')]
    public function showUsers(UserRepository $repository, $page = 1)
    {
        $limit = 5; // Nombre d'utilisateurs par page
        $offset = ($page - 1) * $limit;

        // Récupérer les utilisateurs pour la page actuelle
        $users = $repository->findBy([], null, $limit, $offset);

        // Calculer le nombre total d'entrées et le nombre total de pages
        $totalUsers = count($repository->findAll());
        $totalPages = ceil($totalUsers / $limit);

        return $this->render('user/show.html.twig', [
            'users' => $users,
            'total_entries' => $totalUsers,
            'total_pages' => $totalPages,
            'page' => $page
        ]);
    }
}
