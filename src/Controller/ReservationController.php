<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;



#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(Request $request, ReservationRepository $reservationRepository): Response
    {
        // Récupérer les statistiques sur les réservations
        $reservationStats = $reservationRepository->getReservationStats();

        // Définir le nombre maximal de réservations par page
        $maxReservationsPerPage = 6;

        // Récupérer toutes les réservations
        $totalReservations = $reservationRepository->count([]);

        // Calculer le nombre total de pages
        $totalPages = ceil($totalReservations / $maxReservationsPerPage);

        // Récupérer le numéro de page à partir de la requête, par défaut 1
        $page = $request->query->getInt('page', 1);

        // Calculer l'offset pour la pagination
        $offset = ($page - 1) * $maxReservationsPerPage;

        // Récupérer les réservations pour la page actuelle
        $reservations = $reservationRepository->findBy([], null, $maxReservationsPerPage, $offset);

        // Rendre la vue avec les réservations paginées, les statistiques de réservation, le nombre total de pages et le numéro de page
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'reservationStats' => $reservationStats,
            'total_pages' => $totalPages,
            'page' => $page,
            'maxReservationsPerPage' => $maxReservationsPerPage, // Assurez-vous que cette ligne est présente
        ]);
    }



    #[Route('/front', name: 'app_reservation_indexFront', methods: ['GET'])]
    public function indexFront(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/indexFront.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    #[Route('/new/back', name: 'app_reservation_newBack', methods: ['GET', 'POST'])]
    public function newBack(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/newBack.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
    #[Route('/{id}/front', name: 'app_reservation_showFront', methods: ['GET'])]
    public function showFront(Reservation $reservation): Response
    {
        return $this->render('reservation/showFront.html.twig', [
            'reservation' => $reservation,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/front', name: 'app_reservation_deleteFront', methods: ['POST'])]
    public function deleteFront(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_indexFront', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/reservation/{id}/pdf', name: 'app_reservation_generate_pdf', methods: ['GET'])]
    public function generatePdf(Request $request, $id)
    {
        // Récupérer la réservation depuis la base de données
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);

        // Récupérer l'équipement associé à la réservation
        $equipement = $reservation->getIdEquipement();

        // Créer une instance de FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        // Ajouter un bordure à la page entière
        $pdf->Rect(5, 5, 200, 287); // x, y, largeur, hauteur
        // Ajouter le logo
        $pdf->Image('images/logo.jpg', 10, 10, 30);
        // Ajouter des lignes
        $pdf->SetLineWidth(0.5);
        $pdf->SetDrawColor(0, 0, 0); // Couleur des lignes (noir)


        $pdf->Ln(50);
        $pdf->SetTextColor(0, 0, 128);
        // Ajouter le titre du contrat
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 20, 'Contrat de Reservation avec L\'ESPORT', 0, 1, 'C');

        // Espacement
        $pdf->Ln(5);
        $pdf->SetTextColor(0, 0, 0); // Noir
        // Ajouter la phrase supplémentaire
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, 'Je soussigne, organisateur de ce tournoi, atteste de lacceptation de votre demande de reservation des equipements indiques ci-dessus.', 0, 'C');

        // Espacement
        $pdf->Ln(5);
        $pdf->SetTextColor(0, 0, 0); // Noir

        // Ajouter les détails de la réservation au PDF
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(40, 10, 'ID:', 0, 0);
        $pdf->Cell(0, 10, $reservation->getId(), 0, 1);

        $pdf->Cell(40, 10, 'Nom:', 0, 0);
        $pdf->Cell(0, 10, $reservation->getNom(), 0, 1);
        $pdf->Cell(40, 10, 'Date Debut:', 0, 0);
        $pdf->Cell(0, 10, $reservation->getDatedebutres()->format('Y-m-d'), 0, 1);

        $pdf->Cell(40, 10, 'Date Fin:', 0, 0);
        $pdf->Cell(0, 10, $reservation->getDatefinres()->format('Y-m-d'), 0, 1);

        $pdf->Cell(40, 10, 'Type:', 0, 0);
        $pdf->Cell(0, 10, $reservation->getType(), 0, 1);

        $pdf->Cell(40, 10, 'Deposit:', 0, 0);
        $pdf->Cell(0, 10, $reservation->getDeposit(), 0, 1);

        // Ajoutez les autres détails de la réservation de la même manière

        $pdf->Cell(40, 10, 'Equipement:', 0, 0);
        $pdf->Cell(0, 10, $equipement->getNom(), 0, 1);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Signature:', 0, 1, 'C');

        $pdf->Image('images/signature.png', 50, null, 40);

        // Ajouter l'image du cachet
        $pdf->Image('images/cachet.png', 80, null, 40);
        // Générer le PDF
        $pdfContent = $pdf->Output('S'); // Get the PDF content as string

        // Créer une réponse HTTP avec le contenu PDF
        $response = new Response($pdfContent);

        // Ajouter les en-têtes appropriés pour le téléchargement du PDF
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="contrat_reservation.pdf"');

        // Terminer la réponse Symfony
        return $response;
    }
    #[Route('/reservation/search', name: 'app_reservation_search', methods: ['GET'])]
    public function search(Request $request, ReservationRepository $reservationRepository): Response
    {

        $searchTerm = $request->query->get('q');

        // Recherche dans les réservations en fonction du nom
        $reservation = $reservationRepository->findOneByNom($searchTerm);

        if ($reservation !== null) {
            // Redirige vers la page des détails de la réservation
            return $this->redirectToRoute('app_reservation_show', ['id' => $reservation->getId()]);
        }

        // Si aucune réservation n'est trouvée, affiche une vue avec un message d'erreur
        return $this->render('reservation/not_found.html.twig', [
            'searchTerm' => $searchTerm,
        ]);
    }
}
