<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Billet;
use App\Form\UserType;
use App\Form\BilletFormType;
use App\Form\UserAuthType;
use App\Repository\BilletRepository;
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



class BilletController extends AbstractController
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





    #[Route('/billet/add', name: 'app_billet_add')]
    public function addUser(Request $request): Response
    {
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/billet/addadminbillet', name: 'app_ins_b')]
    public function addBilletadmin(Request $request): Response
    {
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_billets');
        }

        return $this->render('Billet/addadminbillet.html.twig', [
            'form' => $form->createView()
        ]);
    }



    #[Route('/showBillets', name: 'app_show_billets')]
    public function showUsers(BilletRepository $repository)
    {
        $billets = $repository->findAll();
        return $this->render('Billet/showbillet.html.twig', [
            'billets' => $billets
        ]);
    }



    /*    #[Route('/showBillet/{id}', name: 'app_showUser')]
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
*/
    /*
    #[Route('/user/get/all', name: 'app_get_all_user')]
    public function getAll(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('user/listusers.html.twig', [
            'users' => $users
        ]);
    }
*/

    /*
    #[Route('/auth', name: 'app_auth')]
    public function authent()
    {

        return $this->render('user/authentification.html.twig', []);
    }
*/






    #[Route('billet/update/{id}', name: 'app_edit_billets')]
    public function updateBillet(ManagerRegistry $manager, $id, BilletRepository $rep, Request $req)
    {
        $billet = $rep->find($id);
        if (!$billet) {
            throw $this->createNotFoundException('Billet not found');
        }

        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $manager->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_show_billets');
        }

        return $this->render('billet/editbillet.html.twig', ['f' => $form->createView()]);
    }





    #[Route('billet/delete/{id}', name: 'app_billet_delete')]
    public function deleteBillet(ManagerRegistry $manager, $id, BilletRepository $rep): Response
    {
        $billet = $rep->find($id);
        if (!$billet) {
            throw $this->createNotFoundException('Billet not found');
        }

        $entityManager = $manager->getManager();

        // Vérifier s'il existe des billets associés à cet utilisateur
        $billets = $billet->getBillets();
        if ($billets->count() > 0) {
            $this->addFlash('danger', 'Unable to delete user. User has associated billets.');
            return $this->redirectToRoute('app_show_billets');
        }

        // Supprimer l'utilisateur s'il n'y a pas de billets associés
        $entityManager->remove($billet);
        $entityManager->flush();

        return $this->redirectToRoute('app_show_billets');
    }


    /**
     * @Route("/authors/{id}", name="author_details")
     */
    public function userDetails($id)
    {
        return $this->render('user/showBillet.html.twig', ['id' => $id]);
    }
}
