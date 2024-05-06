<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Historique;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Knp\Component\Pager\PaginatorInterface;
use DateTime;


#[Route('/evenement')]
class EvenementController extends AbstractController
{


                    // affiche back
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $criteria = $request->query->get('criteria');

        // Construction de la requête de base
        $queryBuilder = $evenementRepository->createQueryBuilder('e');

        // Appliquer le critère de tri automatique
        $sortField = $this->getSortField($criteria);
        if ($sortField) {
            $queryBuilder->orderBy($sortField['field'], $sortField['order']);
        }

        // Définir les options du paginateur
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $page,
            6 // Nombre d'éléments par page
        );

        return $this->render('evenement/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    private function getSortField($criteria): ?array
    {
        $sortFields = [
            'nomevent_asc' => ['field' => 'e.nomevent', 'order' => 'ASC'],
            'nomevent_desc' => ['field' => 'e.nomevent', 'order' => 'DESC'],
            'lieu_asc' => ['field' => 'e.lieu', 'order' => 'ASC'],
            'lieu_desc' => ['field' => 'e.lieu', 'order' => 'DESC'],
        ];

        return $sortFields[$criteria] ?? null;
    }
    
                 
    
    #[Route('/front', name: 'app_evenement_indexFront', methods: ['GET'])]
    public function indexFront(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer tous les événements non paginés depuis le repository
        $evenements = $evenementRepository->findAll();
    
        // Paginer les événements
        $pagination = $paginator->paginate(
            $evenements, // Les données à paginer
            $request->query->getInt('page', 1), // Le numéro de page à afficher, 1 par défaut
            7 // Nombre d'éléments par page
        );
    
        // Rendre la vue Twig avec les événements paginés
        return $this->render('evenement/indexFront.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
           
              // ajouter

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            $identifier = $evenement->getId();
            $qrCode = new QrCode('Evenement-' . $identifier);
            $qrCode->setSize(300);
            $qrOutputInterface = new PngWriter();
            $qrCodeString = $qrOutputInterface->write($qrCode)->getString();
            $qrCodeBase64 = base64_encode($qrCodeString);
            $evenement->setQrCode($qrCodeBase64);

            $historique = new Historique();
            $historique->setOperationType('Create');
            $historique->setDescription('Created new Evenement: ' . $evenement->getNomevent());
            $historique->setOperationDate(new \DateTime());
            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }
          
           // affiche details front
    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

                    // afficher front
    #[Route('/{id}/front', name: 'app_evenement_showFront', methods: ['GET'])]
    public function showFront(Evenement $evenement): Response
    {
        return $this->render('evenement/showFront.html.twig', [
            'evenement' => $evenement,
        ]);
    }


                            // modifier
    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $historique = new Historique();
            $historique->setOperationType('Update');
            $historique->setDescription('Updated Evenement: ' . $evenement->getNomevent());
            $historique->setOperationDate(new \DateTime());
            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

                 //supprimer

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evenement->getNomevent(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);

            $historique = new Historique();
            $historique->setOperationType('Delete');
            $historique->setDescription('Deleted Evenement: ' . $evenement->getNomevent());
            $historique->setOperationDate(new \DateTime());
            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_evenement_index');
    }


    #[Route('/search', name: 'app_evenement_search', methods: ['GET'])]
    public function search(Request $request, EvenementRepository $evenementRepository): Response
    {
        $searchTerm = $request->query->get('q');
        $location = $request->query->get('location');
        $dateStr = $request->query->get('date'); // Récupérer la chaîne de caractères de date
    
        // Convertir la chaîne de caractères de date en objet DateTime
        $date = $dateStr ? new \DateTime($dateStr) : null;
    
        // Vérifier si $date est null
        if ($date === null) {
            // Traitez le cas où la date est null, par exemple en affichant un message d'erreur ou en redirigeant l'utilisateur
            // return $this->redirectToRoute('route_to_handle_null_date');
        }
    
        // Recherche dans les événements en fonction des critères fournis
        $evenements = $evenementRepository->findBySearchCriteria($searchTerm, $location, $date);
    
        return $this->render('evenement/search.html.twig', [
            'evenements' => $evenements,
            'searchTerm' => $searchTerm,
            'location' => $location,
            'date' => $date,
        ]);
    }
    


               // pdf
    #[Route('/evenement/{id}/generate-pdf', name: 'app_evenement_generate_pdf', methods: ['GET'])]
public function generatePdf(Request $request, Evenement $evenement): Response
    {
        // Rendu de la vue Twig pour le PDF
        $html = $this->renderView('evenement/pdf.html.twig', [
            'evenement' => $evenement,
        ]);

        // Initialisation de Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // (Optionnel) Configuration de Dompdf
        $dompdf->setPaper('A4', 'portrait');

        // Rendu du PDF
        $dompdf->render();

        // Envoi de la réponse PDF
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
   
    
}