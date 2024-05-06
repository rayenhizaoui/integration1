<?php

namespace App\Controller;

use Mpdf\Mpdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Snappy\Pdf;
use App\Entity\User;
use App\Entity\Billet;
use App\Form\UserType;
use App\Form\UserAuthType;
use App\Form\BilletFormType;
use App\Repository\UserRepository;
use App\Repository\BilletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Header\MimeTypeHeader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;



use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;



class BilletController extends AbstractController
{
    private $authorizationChecker;
    private $entityManager;
    private $passwordEncoder;
    private $passwordHasher;
    private $pdfService;
    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker, UserPasswordEncoderInterface $passwordEncoder, Pdf $pdfService)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->pdfService = $pdfService;
    }







    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addUser(Request $request, UserRepository $userRepository): Response
    {
        $user = new User;



        if (!$userRepository->isBanned($user)) {
            // Ajouter un message flash et rediriger vers la page de bannissement
            // $flashBag->add('danger', 'Vous êtes banni. Vous ne pouvez pas ajouter de billet.');
            return new RedirectResponse($this->generateUrl('app_user_banned'));
        } else

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

*/



    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addUser(Request $request, UserRepository $userRepository, Security $security): Response
    {
        // Obtenir l'utilisateur authentifié actuel
        $user = $security->getUser();

        // Vérifier si l'utilisateur est banni
        if ($user && $userRepository->isBanned($user)) {
            // Utilisateur banni, rediriger vers la page d'interdiction
            return $this->redirectToRoute('app_user_banned');
        }

        // L'utilisateur n'est pas banni, continuer avec l'ajout de billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            // Rediriger vers une autre page après l'ajout réussi
            return $this->redirectToRoute('app_login');
        }

        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/



    // #[Route('/billet/add', name: 'app_billet_add')]
    // public function addBillet(Request $request, UserRepository $userRepository): Response
    // {
    //     // Récupérer l'utilisateur actuellement connecté
    //     $user = $this->getUser();

    //     // Vérifier si l'utilisateur est banni
    //     if ($user && $userRepository->isBanned($user)) {
    //         // Rediriger vers une page spécifique pour l'utilisateur banni
    //         return $this->redirectToRoute('app_user_banned'); // Remplacez 'app_user_banned' par la route de votre choix
    //     }

    //     // Si l'utilisateur n'est pas banni, continuer avec l'ajout du billet
    //     $billet = new Billet();
    //     $form = $this->createForm(BilletFormType::class, $billet);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($billet);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_login');
    //     }

    //     return $this->render('Billet/billet.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }





    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addBillet(Request $request, UserRepository $userRepository): Response
    {
        // Créer une instance de Billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuellement connecté
            $user = $this->getUser();

            // Vérifier si l'utilisateur est banni
            if ($user && $userRepository->isBanned($user)) {
                // Rediriger vers la page de bannissement
                return $this->redirectToRoute('app_user_banned');
            }

            // Enregistrer le billet dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            // Rediriger vers une autre page après l'ajout du billet
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire n'est pas encore soumis ou n'est pas valide, afficher le formulaire
        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/



    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addBillet(Request $request, UserRepository $userRepository): Response
    {
        // Créer une instance de Billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuellement connecté
            $user = $this->getUser();

            // Vérifier si l'utilisateur est banni
            if ($user && $userRepository->findOneBy(['username' => $user->getUsername(), 'banned' => true])) {
                // Rediriger vers la page de bannissement
                return $this->redirectToRoute('app_user_banned');
            }

            // Enregistrer le billet dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            // Rediriger vers une autre page après l'ajout du billet
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire n'est pas encore soumis ou n'est pas valide, afficher le formulaire
        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/ /////////


    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addBillet(Request $request, UserRepository $userRepository): Response
    {
        // Créer une instance de Billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuellement connecté
            $user = $this->getUser();

            // Vérifier si l'utilisateur est banni
            if ($user && $userRepository->findOneBy(['username' => $user->getUsername(), 'banned' => true])) {
                // Rediriger vers la page de bannissement
                return $this->redirectToRoute('app_user_banned');
            }

            // Enregistrer le billet dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            // Rediriger vers une autre page après l'ajout du billet
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire n'est pas encore soumis ou n'est pas valide, afficher le formulaire
        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }

*/



    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addBillet(Request $request, UserRepository $userRepository): Response
    {
        // Créer une instance de Billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuellement connecté
            $user = $this->getUser();

            // Vérifier si l'utilisateur est banni
            if ($user) {
                $banned = $userRepository->findOneBy(['username' => $user->getUsername(), 'banned' => true]);
                if ($banned) {
                    // Utilisateur banni, afficher un message de débogage
                    $this->addFlash('danger', 'Vous êtes banni. Vous ne pouvez pas ajouter de billet.');
                    // Rediriger vers la page de bannissement
                    return $this->redirectToRoute('app_user_banned');
                }
            }

            // Enregistrer le billet dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            // Rediriger vers une autre page après l'ajout du billet
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire n'est pas encore soumis ou n'est pas valide, afficher le formulaire
        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/


    #[Route('/billet/add', name: 'app_billet_add')]
    public function addBillet(Request $request, UserRepository $userRepository): Response
    {
        // Créer une instance de Billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuellement connecté
            $user = $this->getUser();

            // Vérifier si l'utilisateur est banni
            if ($user) {
                $banned = $userRepository->isBanned($user);
                if ($banned) {
                    // Utilisateur banni, afficher un message de débogage
                    $this->addFlash('danger', 'Vous êtes banni. Vous ne pouvez pas ajouter de billet.');
                    // Rediriger vers la page de bannissement
                    return $this->redirectToRoute('app_user_banned');
                }
            }

            // Enregistrer le billet dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            // Rediriger vers une autre page après l'ajout du billet
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire n'est pas encore soumis ou n'est pas valide, afficher le formulaire
        return $this->render('billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /*#[Route('/billet/add', name: 'app_billet_add')]
    public function addUser(Request $request, UserRepository $userRepository, SessionInterface $session): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est banni
        if ($userRepository->isBanned($user)) {
            // Rediriger vers la page de bannissement
            $session->getFlashBag()->add('danger', 'Vous êtes banni. Vous ne pouvez pas ajouter de billet.');
            return new RedirectResponse($this->generateUrl('app_user_banned'));
        }

        // Si l'utilisateur n'est pas banni, continuer avec l'ajout du billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $billet->setUser($user); // Ajouter l'utilisateur au billet
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }

*/


    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addUser(Request $request, UserRepository $userRepository, SessionInterface $session, FlashBagInterface $flashBag): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est banni
        if ($userRepository->isBanned($user)) {
            // Ajouter un message flash et rediriger vers la page de bannissement
            $flashBag->add('danger', 'Vous êtes banni. Vous ne pouvez pas ajouter de billet.');
            return new RedirectResponse($this->generateUrl('app_user_banned'));
        }

        // Si l'utilisateur n'est pas banni, continuer avec l'ajout du billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $billet->setUser($user); // Ajouter l'utilisateur au billet
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/



    /*    #[Route('/billet/add', name: 'app_billet_add')]
    public function addBillet(Request $request, UserRepository $userRepository, SessionInterface $session, FlashBagInterface $flashBag): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté
               if (!$user) {
            // Rediriger vers la page de connexion ou afficher un message d'erreur
            $flashBag->add('danger', 'Vous devez être connecté pour ajouter un billet.');
            return new RedirectResponse($this->generateUrl('app_login'));
        }

        // Vérifier si l'utilisateur est banni
        if ($userRepository->isBanned($user)) {
            // Ajouter un message flash et rediriger vers la page de bannissement
            $flashBag->add('danger', 'Vous êtes banni. Vous ne pouvez pas ajouter de billet.');
            return new RedirectResponse($this->generateUrl('app_user_banned'));
        }

        // Si l'utilisateur n'est pas banni et est connecté, continuer avec l'ajout du billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $billet->setUser($user); // Ajouter l'utilisateur au billet
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/



    /*       #[Route('/billet/add', name: 'app_billet_add')]
    public function addUser1(Request $request, UserRepository $userRepository, SessionInterface $session, FlashBagInterface $flashBag): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est banni
        if (!$userRepository->isBanned($user)) {
            // Ajouter un message flash et rediriger vers la page de bannissement
            $flashBag->add('danger', 'Vous êtes banni. Vous ne pouvez pas ajouter de billet.');
            return new RedirectResponse($this->generateUrl('app_user_banned'));
        }

        // Si l'utilisateur n'est pas banni, continuer avec l'ajout du billet
        $billet = new Billet();
        $form = $this->createForm(BilletFormType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $billet->setUser($user); // Ajouter l'utilisateur au billet
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($billet);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Billet/billet.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/


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



    /*    #[Route('/showBillets', name: 'app_show_billets')]
    public function showUsers(BilletRepository $repository)
    {
        $billets = $repository->findAll();
        return $this->render('Billet/showbillet.html.twig', [
            'billets' => $billets
        ]);
    }
*/



    #[Route('/showBillets/{page}', name: 'app_show_billets')]
    public function showUsers(BilletRepository $repository, $page = 1)
    {
        $limit = 5; // Nombre d'utilisateurs par page
        $offset = ($page - 1) * $limit;

        // Récupérer les utilisateurs pour la page actuelle
        $billets = $repository->findBy([], null, $limit, $offset);

        // Calculer le nombre total d'entrées et le nombre total de pages
        $totalBillets = count($repository->findAll());
        $totalPages = ceil($totalBillets / $limit);

        return $this->render('Billet/showbillet.html.twig', [
            'billets' => $billets,
            'total_entries' => $totalBillets,
            'total_pages' => $totalPages,
            'page' => $page
        ]);
    }






    /*   #[Route('/pdf', name: 'app_pdf')]
    public function downloadPDF(): Response
    {
        // Récupérer les données à partir de la base de données ou d'une autre source
        $billetData = []; // Remplacez ceci par votre logique pour récupérer les données des billets

        // Générer le contenu HTML du tableau
        $html = $this->renderView('showbillet.html.twig', [
            'billets' => $billetData, // Passer les données à votre template Twig
        ]);

        // Créer une instance de Mpdf
        $mpdf = new Mpdf();

        // Ajouter le contenu HTML au PDF
        $mpdf->WriteHTML($html);

        // Générer le nom du fichier PDF
        $fileName = 'billet_list.pdf';

        // Envoyer le PDF au navigateur pour le téléchargement
        return new Response(
            $mpdf->Output($fileName, 'D'),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }
*/


    /*    #[Route('/pdf', name: 'app_pdf')]
    public function downloadPDF(): Response
    {
        // Récupérer les données à partir de la base de données ou d'une autre source
        $billetData = []; // Remplacez ceci par votre logique pour récupérer les données des billets

        // Générer le contenu HTML du tableau
        $html = $this->renderView('Billet/showbillet.html.twig', [
            'billets' => $billetData, // Passer les données à votre template Twig
        ]);

        // Options pour la génération PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        // Créer une instance de Dompdf avec les options
        $dompdf = new Dompdf($options);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $fileName = 'billet_list.pdf';

        // Envoyer le PDF au navigateur pour le téléchargement
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }
*/



    /*    #[Route('/pdf', name: 'app_pdf')]
    public function downloadPDF(): Response
    {
        // Récupérer les données à partir de la base de données ou d'une autre source
        $billetData = []; // Remplacez ceci par votre logique pour récupérer les données des billets

        // Générer le contenu HTML du tableau
        $html = $this->renderView('Billet/showbillet.html.twig', [
            'billets' => $billetData, // Passer les données à votre template Twig
        ]);

        // Options pour la génération PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        // Créer une instance de Dompdf avec les options
        $dompdf = new Dompdf($options);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $fileName = 'billet_list.pdf';

        // Envoyer le PDF au navigateur pour le téléchargement
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }
*/

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





    #[Route('/showBillets/{page}/download', name: 'app_download_billets')]
    public function showUsersDownload(BilletRepository $repository, $page = 1)
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $html = $this->renderView('Billet/showbillet.html.twig');
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fichier = 'user-data-' . $this->getUser()->getRoles() . '.pdf';
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);
        return new Response();
    }






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




    /*    #[Route('/download/billets/pdf', name: 'app_download_billets_pdf')]
    public function downloadBilletsPdf(Pdf $pdf, BilletRepository $repository): Response
    {
        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Générer le contenu HTML à partir de la vue Twig
        $html = $this->renderView('Billet/showbillet.html.twig', ['billets' => $billets]);

        // Générer le PDF à partir du contenu HTML
        $pdfContent = $pdf->getOutputFromHtml($html);

        // Créer une réponse PDF
        $response = new Response($pdfContent);

        // Ajouter les en-têtes pour indiquer que c'est un fichier PDF téléchargeable
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="billets.pdf"');

        return $response;
    }*/





    /*    #[Route('/download/billets/pdf', name: 'app_download_billets_pdf')]
    public function downloadBilletsPdf(Pdf $pdf, BilletRepository $repository): Response
    {
        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Calculer le nombre total de billets
        $totalBillets = count($billets);

        // Générer le contenu HTML à partir de la vue Twig
        $html = $this->renderView('Billet/showbillet.html.twig', [
            'billets' => $billets,
            'total_entries' => $totalBillets,
            //'total_pages' => $totalPages,
            'page' => $page
            // Passer le nombre total de billets
        ]);

        // Générer le PDF à partir du contenu HTML
        $pdfContent = $pdf->getOutputFromHtml($html);

        // Créer une réponse PDF
        $response = new Response($pdfContent);

        // Ajouter les en-têtes pour indiquer que c'est un fichier PDF téléchargeable
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="billets.pdf"');

        return $response;
    }*/




    /*    #[Route('/download/billets/pdf', name: 'app_download_billets_pdf')]
    public function downloadBilletsPdf(Pdf $pdf, BilletRepository $repository): Response
    {
        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Calculer le nombre total de billets
        $totalBillets = count($billets);

        // Définir manuellement la variable 'page'
        $page = 1; // ou n'importe quelle autre valeur que vous voulez

        // Générer le contenu HTML à partir de la vue Twig
        $html = $this->renderView('Billet/showbillet.html.twig', [
            'billets' => $billets,
            'total_entries' => $totalBillets, // Passer le nombre total de billets
            'page' => $page // Définir la variable 'page'
        ]);

        // Générer le PDF à partir du contenu HTML
        $pdfContent = $pdf->getOutputFromHtml($html);

        // Créer une réponse PDF
        $response = new Response($pdfContent);

        // Ajouter les en-têtes pour indiquer que c'est un fichier PDF téléchargeable
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="billets.pdf"');

        return $response;
    }*/


    /*    #[Route('/download/billets/pdf', name: 'app_download_billets_pdf')]
    public function downloadBilletsPdf(Pdf $pdf, BilletRepository $repository): Response
    {
        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Calculer le nombre total de billets
        $totalBillets = count($billets);

        // Calculer le nombre total de pages (en supposant qu'il y a 5 billets par page)
        $totalPages = ceil($totalBillets / 5); // Modifier 5 en fonction du nombre d'éléments affichés par page

        // Définir manuellement la variable 'page'
        $page = 1; // ou n'importe quelle autre valeur que vous voulez

        // Générer le contenu HTML à partir de la vue Twig
        $html = $this->renderView('Billet/showbillet.html.twig', [
            'billets' => $billets,
            'total_entries' => $totalBillets, // Passer le nombre total de billets
            'total_pages' => $totalPages, // Passer le nombre total de pages
            'page' => $page // Définir la variable 'page'
        ]);

        // Générer le PDF à partir du contenu HTML
        $pdfContent = $pdf->getOutputFromHtml($html);

        // Créer une réponse PDF
        $response = new Response($pdfContent);

        // Ajouter les en-têtes pour indiquer que c'est un fichier PDF téléchargeable
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="billets.pdf"');

        return $response;
    }*/





    /*    #[Route('/showBillets/{page}/download', name: 'app_download_billets')]
    public function showUsersDownload1(BilletRepository $repository, $page = 1)
    {
        $pdf = $this->pdfService;

        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Calculer le nombre total de billets
        $totalBillets = count($billets);

        // Calculer le nombre total de pages (en supposant qu'il y a 5 billets par page)
        $totalPages = ceil($totalBillets / 5); // Modifier 5 en fonction du nombre d'éléments affichés par page

        // Définir manuellement la variable 'page'
        $page = 1; // ou n'importe quelle autre valeur que vous voulez



        // Générer le contenu HTML à partir de la vue Twig
        $html = $this->renderView('Billet/showbillet.html.twig', [
            'billets' => $billets,
            'total_entries' => $totalBillets, // Passer le nombre total de billets
            'total_pages' => $totalPages, // Passer le nombre total de pages
            'page' => $page // Définir la variable 'page'
        ]);

        // Générer le PDF à partir du contenu HTML
        $pdf = $this->get('knp_snappy.pdf');
        $pdf->generateFromHtml($html, '/chemin/vers/enregistrement/fichier.pdf');

        // Créer une réponse PDF
        $response = new Response();

        // Ajouter les en-têtes pour indiquer que c'est un fichier PDF téléchargeable
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="billets.pdf"');

        return $response;
    }
    */



    #[Route('/showBillets/{page}/download', name: 'app_download_billets')]
    public function showUsersDownload1(BilletRepository $repository, $page = 1)
    {
        $pdf = $this->pdfService;

        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Récupérer la liste des billets depuis le référentiel
        $billets = $repository->findAll();

        // Calculer le nombre total de billets
        $totalBillets = count($billets);

        // Calculer le nombre total de pages (en supposant qu'il y a 5 billets par page)
        $totalPages = ceil($totalBillets / 5); // Modifier 5 en fonction du nombre d'éléments affichés par page

        // Définir manuellement la variable 'page'
        $page = 1; // ou n'importe quelle autre valeur que vous voulez

        // Générer le contenu HTML à partir de la vue Twig
        $html = $this->renderView('Billet/Pdfbillet.html.twig', [
            'billets' => $billets,
            'total_entries' => $totalBillets, // Passer le nombre total de billets
            'total_pages' => $totalPages, // Passer le nombre total de pages
            'page' => $page // Définir la variable 'page'
        ]);

        // Générer le PDF à partir du contenu HTML
        $pdf = $this->pdfService;
        $pdf->generateFromHtml($html, 'C:\Users\Admin\Documents\PDF\fichier.pdf');

        // Créer une réponse PDF
        $response = new Response();

        // Ajouter les en-têtes pour indiquer que c'est un fichier PDF téléchargeable
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="billets.pdf"');

        //return $response;
        return $this->redirectToRoute('app_show_billets');
    }
}
