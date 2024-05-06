<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Billet;
use App\Form\UserType;
use App\Form\UserAuthType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;

use Doctrine\ORM\EntityManagerInterface;



use Doctrine\Persistence\ManagerRegistry;



class UserController extends AbstractController
{
    private $authorizationChecker;
    private $entityManager;
    private $passwordEncoder;
    private $passwordHasher;
    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }





    #[Route('/user/add', name: 'app_user_add')]
    public function addUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/addadmin', name: 'app_ins')]
    public function addUseradmin(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_users');
        }

        return $this->render('user/addadmin.html.twig', [
            'form' => $form->createView()
        ]);
    }



    #[Route('/showUsers', name: 'app_show_users')]
    public function showUsers(UserRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('user/show.html.twig', [
            'users' => $users
        ]);
    }



    #[Route('/showUser/{id}', name: 'app_showUser')]
    public function showUser($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/user/get/all', name: 'app_get_all_user')]
    public function getAll(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('user/listusers.html.twig', [
            'users' => $users
        ]);
    }



    #[Route('/auth', name: 'app_auth')]
    public function authent()
    {

        return $this->render('user/authentification.html.twig', []);
    }







    #[Route('user/update/{id}', name: 'app_edit_users')]
    public function updateUser(ManagerRegistry $manager, $id, UserRepository $rep, Request $req)
    {
        $user = $rep->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_show_users');
        }

        return $this->render('user/edit.html.twig', ['f' => $form->createView()]);
    }





    #[Route('user/delete/{id}', name: 'app_user_delete')]
    public function deleteUser(ManagerRegistry $manager, $id, UserRepository $rep): Response
    {
        $user = $rep->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager = $manager->getManager();

        // Vérifier s'il existe des billets associés à cet utilisateur
        $billets = $user->getBillets();
        if ($billets->count() > 0) {
            $this->addFlash('danger', 'Unable to delete user. User has associated billets.');
            return $this->redirectToRoute('app_show_users');
        }

        // Supprimer l'utilisateur s'il n'y a pas de billets associés
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_show_users');
    }


    /**
     * @Route("/authors/{id}", name="author_details")
     */
    public function userDetails($id)
    {
        return $this->render('user/showUser.html.twig', ['id' => $id]);
    }
}
