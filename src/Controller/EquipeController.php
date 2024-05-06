<?php

namespace App\Controller;
use App\Entity\Equipe;
use App\Entity\Jeu;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;
use Dompdf\Dompdf;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(Request $request, EquipeRepository $equipeRepository): Response
    {    $sortBy = $request->query->get('sortBy', 'id');
        $sortOrder = $request->query->get('sortOrder', 'ASC');
        $equipes = $equipeRepository->findBy([], [$sortBy => $sortOrder]);

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
            'sortBy' => $sortBy, // Passer la variable sortBy
            'sortOrder' => $sortOrder, 
        ]);
    }
    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('baseFront.html.twig');
    }
    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('baseBack.html.twig');
    }
    #[Route('/front', name: 'app_equipe_index_front', methods: ['GET'])]
    public function indexFront(EquipeRepository $equipeRepository): Response
    {
        $equipes = $equipeRepository->findAll();

        return $this->render('basepageFront.html.twig', [
            'equipes' => $equipes,
        ]);
    }

    #[Route('/new', name: 'app_equipe_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
         
            $nom = $equipe->getNom();
            if (!preg_match('/^[A-Z]/', $nom)) {
                $form->get('nom')->addError(new FormError('Le nom doit commencer par une majuscule.'));
                return $this->renderForm('equipe/new.html.twig', [
                    'equipe' => $equipe,
                    'form' => $form,
                ]);
            }
            $nbrJoueur = $equipe->getNbrJoueur();
            if ($nbrJoueur > 100) {
                $form->get('nbrJoueur')->addError(new FormError('Le nombre de joueurs doit être inférieur ou égal à 100.'));
                return $this->renderForm('equipe/new.html.twig', [
                    'equipe' => $equipe,
                    'form' => $form,
                ]);
            }
            $entityManager->persist($equipe);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }
    #[Route('/new1', name: 'app_equipe_new_front')]
    public function newFront(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $equipe->getNom();
            if (!preg_match('/^[A-Z]/', $nom)) {
                $form->get('nom')->addError(new FormError('Le nom doit commencer par une majuscule.'));
                return $this->renderForm('equipe/new1.html.twig', [
                    'equipe' => $equipe,
                    'form' => $form,
                ]);
            }
            $nbrJoueur = $equipe->getNbrJoueur();
            if ($nbrJoueur > 100) {
                $form->get('nbrJoueur')->addError(new FormError('Le nombre de joueurs doit être inférieur ou égal à 100.'));
                return $this->renderForm('equipe/new1.html.twig', [
                    'equipe' => $equipe,
                    'form' => $form,
                ]);
            }
            $entityManager->persist($equipe);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_equipe_index_front'); 
        }
    
        return $this->render('equipe/new1.html.twig', [
            'equipe' => $equipe,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/equipe/export-pdf', name: 'app_equipe_export_pdf')]
    public function exportPdf(EquipeRepository $equipeRepository): Response
    {
        $equipes = $equipeRepository->findAll();
        $html = $this->renderView('equipe/export_pdf.html.twig', [
            'equipes' => $equipes,
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
    #[Route('/search-ajax', name: 'app_equipe_search_ajax', methods: ['GET'])]
    public function searchAjax(Request $request, EquipeRepository $equipeRepository): JsonResponse
    {
        $searchTerm = $request->query->get('searchTerm');
        $equipes = $equipeRepository->search($searchTerm);
        return $this->json($equipes, 200, [], ['groups' => 'equipe']);
    }
#[Route('/statistics', name: 'app_equipe_statistics', methods: ['GET'])]
    public function statistics(EquipeRepository $equipeRepository): Response
    {
        $totalTeams = $equipeRepository->countAllTeams();
        $averagePlayersPerTeam = $equipeRepository->calculateAveragePlayersPerTeam();
        $teamsWithEnoughPlayers = $equipeRepository->findTeamsWithEnoughPlayers();
        $teamsWithInsufficientPlayers = $equipeRepository->findTeamsWithInsufficientPlayers();

        $statistics = [
            'total_teams' => $totalTeams,
            'average_players_per_team' => $averagePlayersPerTeam,
            'teams_with_enough_players' => $teamsWithEnoughPlayers,
            'teams_with_insufficient_players' => $teamsWithInsufficientPlayers,
        ];

        return $this->render('equipe/statistiques_equipe.html.twig', [
            'statistics' => $statistics,
        ]);
    }
    #[Route('/chatbot', name: 'app_chatbot', methods: ['GET'])]
    public function chatbot(): Response
    {
        return $this->render('equipe/chatbot.html.twig');
    }
    
}