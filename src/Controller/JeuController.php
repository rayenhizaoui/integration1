<?php

namespace App\Controller;

use App\Entity\Jeu;
use App\Form\JeuType;
use App\Repository\JeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/jeu')]
class JeuController extends AbstractController
{
    public function index(Request $request, JeuRepository $jeuRepository): Response
    {
        $sortBy = $request->query->get('sortBy', 'id');
        $sortOrder = $request->query->get('sortOrder', 'ASC');

        $jeux = $jeuRepository->findBy([], [$sortBy => $sortOrder]);

        return $this->render('jeu/index.html.twig', [
            'jeux' => $jeux,
            'sortBy' => $sortBy, // Passer la variable sortBy
            'sortOrder' => $sortOrder,
        ]);
    }
    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('baseFront.html.twig');
    }
    #[Route('/front', name: 'app_jeu_index_front', methods: ['GET'])]
    public function indexFront(JeuRepository $jeuRepository): Response
    {
        $jeux = $jeuRepository->findAll();

        return $this->render('basepageFront1.html.twig', [
            'jeux' => $jeux,
        ]);
    }
    #[Route('/new', name: 'app_jeu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $jeu = new Jeu();
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $jeu->getNom();
            $type = $jeu->getType();
            if (!preg_match('/^[A-Z]/', $nom)) {
                $form->get('nom')->addError(new FormError('Le nom du jeu doit commencer par une majuscule.'));
                return $this->renderForm('jeu/new.html.twig', [
                    'jeu' => $jeu,
                    'form' => $form,
                ]);
            }
            if (!preg_match('/^[a-zA-Z]+$/', $type)) {
                $form->get('type')->addError(new FormError('Le type doit contenir uniquement des lettres.'));
                return $this->renderForm('jeu/new.html.twig', [
                    'jeu' => $jeu,
                    'form' => $form,
                ]);
            }

            $entityManager->persist($jeu);
            $entityManager->flush();

            return $this->redirectToRoute('app_jeu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jeu/new.html.twig', [
            'jeu' => $jeu,
            'form' => $form,
        ]);
    }
    #[Route('/new1', name: 'app_jeu_new_front')]
    public function newFront(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $jeu = new Jeu();
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $jeu->getNom();
            $type = $jeu->getType();
            if (!preg_match('/^[A-Z]/', $nom)) {
                $form->get('nom')->addError(new FormError('Le nom du jeu doit commencer par une majuscule.'));
                return $this->renderForm('jeu/new1.html.twig', [
                    'jeu' => $jeu,
                    'form' => $form,
                ]);
            }
            if (!preg_match('/^[a-zA-Z]+$/', $type)) {
                $form->get('type')->addError(new FormError('Le type doit contenir uniquement des lettres.'));
                return $this->renderForm('jeu/new1.html.twig', [
                    'jeu' => $jeu,
                    'form' => $form,
                ]);
            }
            $entityManager->persist($jeu);
            $entityManager->flush();

            return $this->redirectToRoute('app_jeu_index_front');
        }

        return $this->render('jeu/new1.html.twig', [
            'jeu' => $jeu,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jeu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jeu $jeu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JeuType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_jeu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jeu/edit.html.twig', [
            'jeu' => $jeu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeu_delete', methods: ['POST'])]
    public function delete(Request $request, Jeu $jeu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $jeu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jeu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jeu_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export-to-excel', name: 'export_to_excel')]
    public function exportToExcel(JeuRepository $jeuRepository): Response
    {
        // Récupérez les jeux depuis le repository ou toute autre logique nécessaire
        $jeux = $jeuRepository->findAll();

        // Créez un objet PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajoutez les en-têtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Type');
        $sheet->setCellValue('D1', 'Score');
        $sheet->setCellValue('E1', 'Resultat');
        // Ajoutez d'autres en-têtes si nécessaire

        // Remplissez les données des jeux dans les cellules
        $row = 2;
        foreach ($jeux as $jeu) {
            $sheet->setCellValue('A' . $row, $jeu->getId());
            $sheet->setCellValue('B' . $row, $jeu->getNom());
            $sheet->setCellValue('C' . $row, $jeu->getType());
            $sheet->setCellValue('D' . $row, $jeu->getScore());
            $sheet->setCellValue('E' . $row, $jeu->getResultat());
            // Ajoutez d'autres données si nécessaire
            $row++;
        }

        // Créez un objet Writer pour Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Créez une réponse HTTP
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        // Définissez les en-têtes de la réponse pour indiquer que c'est un fichier Excel
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="export_jeux.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
    #[Route('/search-ajax', name: 'app_jeu_search_ajax', methods: ['GET'])]
    public function searchAjax(Request $request, JeuRepository $jeuRepository): JsonResponse
    {
        $searchTerm = $request->query->get('searchTerm');
        $jeux = $jeuRepository->search($searchTerm);
        return $this->json($jeux, 200, [], ['groups' => 'jeu']);
    }
}
