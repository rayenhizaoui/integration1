<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\User;
use App\Entity\Billet;
use App\Form\UserType;
use App\Form\UserAuthType;
use Endroid\QrCode\QrCode;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;



class UserController extends AbstractController
{
    private $authorizationChecker;
    private $entityManager;
    private $passwordEncoder;
    private $passwordHasher;
    private $mailer;
    private $pdfService;
    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer, Pdf $pdfService)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        // $this->pdfService = $pdfService;
    }





    // #[Route('/showUsers/download', name: 'app_download_billets')]
    // public function showUsersDownload1(UserRepository $repository)
    // {
    //     $pdf = $this->pdfService;

    //     // Récupérer la liste des billets depuis le référentiel
    //     $users = $repository->findAll();

    //     // Récupérer la liste des billets depuis le référentiel
    //     $users = $repository->findAll();

    //     // Calculer le nombre total de billets
    //     //$totalBillets = count($billets);

    //     // Calculer le nombre total de pages (en supposant qu'il y a 5 billets par page)
    //     //$totalPages = ceil($totalBillets / 5); // Modifier 5 en fonction du nombre d'éléments affichés par page

    //     // Définir manuellement la variable 'page'
    //     //$page = 1; // ou n'importe quelle autre valeur que vous voulez

    //     // Générer le contenu HTML à partir de la vue Twig
    //     $html = $this->renderView('user/show.html.twig', [
    //         'users' => $users,
    //         //'total_entries' => $totalBillets, // Passer le nombre total de billets
    //         //'total_pages' => $totalPages, // Passer le nombre total de pages
    //         //'page' => $page // Définir la variable 'page'
    //     ]);

    //     // Générer le PDF à partir du contenu HTML
    //     $pdf = $this->pdfService;
    //     $pdf->generateFromHtml($html, 'C:\Users\Admin\Documents\PDF\fichier.pdf');

    //     // Créer une réponse PDF
    //     $response = new Response();

    //     // Ajouter les en-têtes pour indiquer que c'est un fichier PDF téléchargeable
    //     $response->headers->set('Content-Type', 'application/pdf');
    //     $response->headers->set('Content-Disposition', 'attachment; filename="billets.pdf"');

    //     return $response;
    // }



    // #[Route('/user/banned', name: 'app_user_banned')]
    // public function banned(): Response
    // {
    //     return $this->render('user/banned.html.twig');
    // }




    //************************************************************************************************************ */

    /*   #[Route('/user/add', name: 'app_user_add')]
    public function addUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoyer un e-mail de confirmation
            $this->sendConfirmationEmail($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
*/
    /*********************************************** */





    // #[Route('/user/ban/{id}', name: 'app_user_ban')]
    // public function banUser($id, UserRepository $userRepository): Response
    // {
    //     $user = $userRepository->find($id);
    //     if (!$user) {
    //         throw $this->createNotFoundException('User not found');
    //     }

    //     // Mettre à jour la propriété 'banned' de l'utilisateur à true par exemple
    //     $user->setBanned(true);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_show_users');
    // }


    // #[Route('/user/unban/{id}', name: 'app_user_unban')]
    // public function unbanUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    // {
    //     $userId = $request->get('id');
    //     $user = $userRepository->find($userId);

    //     if (!$user) {
    //         throw $this->createNotFoundException('User not found');
    //     }

    //     // Débannir l'utilisateur
    //     $user->setBanned(false);

    //     // Enregistrer les modifications dans la base de données
    //     $entityManager->flush();

    //     // Rediriger vers la page de détails de l'utilisateur ou une autre page appropriée
    //     return $this->redirectToRoute('app_show_users', ['id' => $userId]);
    // }









    /*    private function sendConfirmationEmail(User $user): void
    {
        $email = (new Email())
            ->from('rayenhizaoui055@gamil.com')
            ->to($user->getEmail())
            ->subject('Confirmation de création de compte')
            ->html(
                $this->renderView(
                    'user/registration.html.twig',
                    ['user' => $user]
                )
            );

        $this->mailer->send($email);
    }

*/




    /*    #[Route('/user/add', name: 'app_user_add')]
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
*/






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


    /*///////////////tttttttttttttttttttttttt
    #[Route('/showUsers', name: 'app_show_users')]
    public function showUsers(UserRepository $repository)
    {

        $users = $repository->findAll();
        return $this->render('user/show.html.twig', [
            'users' => $users
        ]);
    }
*/ /////////////////////////////////////tttttttttttttttttttttttt




    /*    #[Route('/showUsers', name: 'app_show_users')]
    public function showUsers(Request $request, UserRepository $repository)
    {
        $searchQuery = $request->query->get('q');
        $users = $repository->findAll();

        return $this->render('user/show.html.twig', [
            'users' => $users,
            'searchQuery' => $searchQuery, // Pass searchQuery to the template
        ]);
    }
*/

    #[Route('/showUsers', name: 'app_show_users')]
    public function showUsers(Request $request, UserRepository $repository)
    {
        $searchQuery = $request->query->get('q');
        $sortBy = $request->query->get('sortBy', 'username'); // Default sorting by username
        $users = $repository->findAll();

        return $this->render('user/show.html.twig', [
            'users' => $users,
            'searchQuery' => $searchQuery,
            'sortBy' => $sortBy, // Pass sortBy to the template
        ]);
    }






    /*    #[Route('/showUsers/{page}', name: 'app_show_users')]
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
*/






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











    /*#[Route('/user/qrcodes', name: 'app_user_qrcodes')]
public function generateQRCodes(UserRepository $userRepository): Response
{
    // Récupérer tous les utilisateurs
    $users = $userRepository->findAll();

    // Créer un tableau pour stocker les chemins des QR codes
    $qrCodePaths = [];

    // Générer un QR code pour chaque utilisateur
    foreach ($users as $user) {
        // Générer le contenu du QR code (par exemple, l'URL du profil de l'utilisateur)
        $qrCodeContent = $this->generateUrl('app_showUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Créer un nouvel objet QR code
        $qrCode = new QrCode($qrCodeContent);

        // Définir d'autres paramètres du QR code si nécessaire
        // $qrCode->setSize(300);

        // Générer le chemin pour sauvegarder le QR code
        $qrCodePath = '/path/to/qr_codes/' . $user->getId() . '_qrcode.png';

        // Enregistrer le QR code dans le fichier
        $qrCode->writeFile($qrCodePath);

        // Ajouter le chemin du QR code au tableau
        $qrCodePaths[$user->getId()] = $qrCodePath;
    }

    // Rendre la vue qui affiche les QR codes
    return $this->render('user/qrcodes.html.twig', [
        'qrCodePaths' => $qrCodePaths,
    ]);
}
*/
    /*
#[Route('/user/qrcodes', name: 'app_user_qrcodes')]
public function generateQRCodes(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator): Response
{
    // Récupérer tous les utilisateurs
    $users = $userRepository->findAll();

    // Créer un tableau pour stocker les chemins des QR codes
    $qrCodePaths = [];

    // Générer un QR code pour chaque utilisateur
    foreach ($users as $user) {
        // Générer le contenu du QR code (par exemple, l'URL du profil de l'utilisateur)
        $qrCodeContent = $urlGenerator->generate('app_showUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Créer un nouvel objet QR code
        $qrCode = new QrCode($qrCodeContent);

        // Définir d'autres paramètres du QR code si nécessaire
        // $qrCode->setSize(300);

        // Générer le chemin pour sauvegarder le QR code
        $qrCodePath = '/path/to/qr_codes/' . $user->getId() . '_qrcode.png';

        // Enregistrer le QR code dans le fichier
        $qrCode->writeFile($qrCodePath);

        // Ajouter le chemin du QR code au tableau
        $qrCodePaths[$user->getId()] = $qrCodePath;
    }

    // Rendre la vue qui affiche les QR codes
    return $this->render('user/qrcodes.html.twig', [
        'qrCodePaths' => $qrCodePaths,
    ]);
}*/




    /*#[Route('/user/qrcodes', name: 'app_user_qrcodes')]
public function generateQRCodes(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator): Response
{
    // Récupérer tous les utilisateurs
    $users = $userRepository->findAll();

    // Créer un tableau pour stocker les chemins des QR codes
    $qrCodePaths = [];

    // Générer un QR code pour chaque utilisateur
    foreach ($users as $user) {
        // Générer le contenu du QR code (par exemple, l'URL du profil de l'utilisateur)
        $qrCodeContent = $urlGenerator->generate('app_showUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Créer un nouvel objet QR code
        $qrCode = new QrCode($qrCodeContent);

        // Définir d'autres paramètres du QR code si nécessaire
        // $qrCode->setSize(300);

        // Générer le chemin pour sauvegarder le QR code
        $qrCodePath = '/path/to/qr_codes/' . $user->getId() . '_qrcode.png';

        // Écrire le contenu du QR code dans un fichier
        file_put_contents($qrCodePath, $qrCode->writeString());

        // Ajouter le chemin du QR code au tableau
        $qrCodePaths[$user->getId()] = $qrCodePath;
    }

    // Rendre la vue qui affiche les QR codes
    return $this->render('user/qrcodes.html.twig', [
        'qrCodePaths' => $qrCodePaths,
    ]);
}
*/












    /*#[Route('/user/qrcodes', name: 'app_user_qrcodes')]
public function generateQRCodes(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator): Response
{
    // Récupérer tous les utilisateurs
    $users = $userRepository->findAll();

    // Créer un tableau pour stocker les données base64 des QR codes
    $qrCodeDataUris = [];

    // Générer un QR code pour chaque utilisateur
    foreach ($users as $user) {
        // Générer le contenu du QR code (par exemple, l'URL du profil de l'utilisateur)
        $qrCodeContent = $urlGenerator->generate('app_showUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // Créer un nouvel objet QR code
        $qrCode = new QrCode($qrCodeContent);

        // Définir d'autres paramètres du QR code si nécessaire
        // $qrCode->setSize(300);

        // Obtenir les données base64 du QR code
        $qrCodeDataUri = $qrCode->getDataUri();

        // Ajouter les données base64 du QR code au tableau
        $qrCodeDataUris[$user->getId()] = $qrCodeDataUri;
    }

    // Rendre la vue qui affiche les QR codes
    return $this->render('user/qrcodes.html.twig', [
        'qrCodeDataUris' => $qrCodeDataUris,
    ]);
}
*/









    #[Route('/user/add', name: 'app_user_add')]
    public function addUser(Request $request, ValidatorInterface $validator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoyer l'email de confirmation
            $this->sendConfirmationEmail($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function sendConfirmationEmail(User $user): void
    {
        // Récupérer l'email de l'utilisateur
        $toId = $user->getEmail();
        $subject = 'Confirmation d\'inscription';
        $message = 'Bonjour ' . $user->getUsername() . ', votre inscription a été confirmée avec succès.';

        // Créer une instance de PHPMailer
        $mail = new PHPMailer(true);

        // Configurer les paramètres SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rayenlorkha1@gmail.com';
        $mail->Password = 'vijxbklqgybjxoqx';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Définir l'expéditeur et le destinataire de l'e-mail
        $mail->setFrom('rayenlorkha1@gmail.com', 'RAYEN');
        $mail->addAddress($toId);

        // Définir le sujet et le corps de l'e-mail
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Envoyer l'e-mail
        $mail->send();
    }







    /*    #[Route('/user/search', name: 'app_user_search')]
    public function search(Request $request, UserRepository $userRepository): Response
    {
        // Récupérez les termes de recherche depuis la requête
        $searchTerm = $request->query->get('search');

        // Vérifiez si un terme de recherche a été fourni
        if ($searchTerm) {
            // Recherchez dans la base de données en utilisant le UserRepository
            $users = $userRepository->findBySearchTerm($searchTerm);
        } else {
            // Si aucun terme de recherche n'est fourni, affichez tous les utilisateurs
            $users = $userRepository->findAll();
        }

        return $this->render('user/search.html.twig', [
            'users' => $users,
            'searchTerm' => $searchTerm,
        ]);
    }*/







    /*    // Ajoutez cette méthode à votre UserController
    #[Route('/user/search_ajax', name: 'app_user_search_ajax')]
    public function searchAjax(Request $request, UserRepository $userRepository): JsonResponse
    {
        // Récupérez le terme de recherche depuis la requête AJAX
        $searchTerm = $request->query->get('search');

        // Recherchez dans la base de données en utilisant le UserRepository
        $users = $userRepository->findBySearchTerm($searchTerm);

        // Transformez les résultats en un tableau associatif
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'cin' => $user->getCin(),
                'username' => $user->getUsername(),
                'numero' => $user->getNumero(),
                'email' => $user->getEmail(),
                'adresse' => $user->getAdresse(),
                'password' => $user->getPassword(),
                'role' => $user->getRole(),
                'banned' => $user->getBanned(),
            ];
        }

        // Retournez les résultats sous forme de réponse JSON
        return new JsonResponse($data);
    }*/






    /*    #[Route('/user/search', name: 'app_user_search_ajax')]
    public function searchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        $searchTerm = $request->query->get('search');

        // Utilisez la méthode de recherche appropriée dans votre UserRepository
        $users = $userRepository->search($searchTerm);

        // Convertissez les résultats en un tableau associatif
        $responseData = [];
        foreach ($users as $user) {
            $responseData[] = [
                'id' => $user->getId(),
                'cin' => $user->getCin(),
                'username' => $user->getUsername(),
                'numero' => $user->getNumero(),
                'email' => $user->getEmail(),
                'adresse' => $user->getAdresse(),
                'password' => $user->getPassword(),
                'role' => $user->getRole(),
                'banned' => $user->isBanned() ? 'Banned' : 'Active',
            ];
        }

        // Retournez les résultats sous forme de réponse JSON
        return new JsonResponse($responseData);
    }


*/



    /*    #[Route('/user/search', name: 'app_user_search', methods: ['POST'])]
    public function searchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        $searchTerm = $request->request->get('searchTerm');
        $users = $userRepository->searchByUsername($searchTerm); // Créez cette méthode dans votre UserRepository pour rechercher les utilisateurs par nom d'utilisateur
        $html = $this->renderView('user/_user_table.html.twig', ['users' => $users]);

        return new JsonResponse(['html' => $html]);
    }*/






    /*    #[Route('/user/search', name: 'app_user_search', methods: ['POST'])]
    public function searchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        $searchTerm = $request->request->get('searchTerm');
        $users = $userRepository->searchByUsername($searchTerm);
        $html = $this->renderView('user/_user_table.html.twig', ['users' => $users]);

        // Au lieu de renvoyer une vue HTML directement, renvoyez les données JSON avec la vue HTML
        return new JsonResponse(['html' => $html]);
    }*/





    /*    #[Route('/search-user', name: 'search_user', methods: ['POST'])]
    public function searchUser(Request $request, UserRepository $userRepository): Response
    {
        $searchText = $request->request->get('search');

        // Faites votre logique de recherche ici avec le texte de recherche
        // Par exemple, utilisez la méthode de recherche appropriée du UserRepository
        $users = $userRepository->search($searchText);

        // Renvoyez les résultats au format HTML (par exemple, via un template Twig partiel)
        return $this->render('user/_user_list.html.twig', [
            'users' => $users,
        ]);
    }*/


    #[Route('/search-user', name: 'search_user', methods: ['POST'])]
    public function searchUser(Request $request, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $searchText = $request->request->get('search');

        // Faites votre logique de recherche ici avec le texte de recherche
        // Par exemple, utilisez la méthode de recherche appropriée du UserRepository
        $users = $userRepository->search($searchText);

        // Transformez les résultats en HTML en utilisant le nouveau template
        $html = $this->renderView('user/_user_list.html.twig', [
            'users' => $users,
        ]);

        // Renvoyez le contenu HTML en tant que réponse
        return new Response($html);
    }

    /*public function searchUser(Request $request, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $searchText = $request->request->get('search');

        // Faites votre logique de recherche ici avec le texte de recherche
        // Par exemple, utilisez la méthode de recherche appropriée du UserRepository
        $users = $userRepository->search($searchText);

        // Transformez les résultats en HTML
        $html = $this->renderView('user/_user_list.html.twig', [
            'users' => $users,
        ]);

        // Renvoyez le contenu HTML en tant que réponse
        return new Response($html);
    }*/


    /*   #[Route('/user/search', name: 'app_user_search')]
    public function searchUsers(Request $request, UserRepository $userRepository): Response
    {
        $searchQuery = $request->query->get('q');
        $sortBy = $request->query->get('sortBy', 'username'); // Default sorting by username

        $queryBuilder = $userRepository->createQueryBuilder('u');

        // If search query exists, add condition to filter users
        if ($searchQuery) {
            $queryBuilder->andWhere('u.username LIKE :searchQuery OR u.email LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        // Add sorting
        $queryBuilder->orderBy('u.' . $sortBy, 'ASC');

        $users = $queryBuilder->getQuery()->getResult();

        return $this->render('user/search.html.twig', [
            'users' => $users,
            'searchQuery' => $searchQuery,
            'sortBy' => $sortBy,
        ]);
    }*/
    #[Route('/user/search', name: 'app_user_search')]
    public function searchUsers(Request $request, UserRepository $userRepository): Response
    {
        $searchQuery = $request->query->get('q');
        $sortBy = $request->query->get('sortBy', 'username'); // Default sorting by username

        $queryBuilder = $userRepository->createQueryBuilder('u');

        // If search query exists, add condition to filter users
        if ($searchQuery) {
            $queryBuilder->andWhere('u.username LIKE :searchQuery OR u.email LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        // Add sorting
        $queryBuilder->orderBy('u.' . $sortBy, 'ASC');

        $users = $queryBuilder->getQuery()->getResult();

        return $this->render('user/show.html.twig', [
            'users' => $users,
            'searchQuery' => $searchQuery, // Pass searchQuery to the template
            'sortBy' => $sortBy,
        ]);
    }
}
