<?php

namespace App\Controller;

use App\Entity\Equipement;
use App\Form\EquipementType;
use App\Repository\EquipementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

#[Route('/equipement')]
class EquipementController extends AbstractController
{
    #[Route('/', name: 'app_equipement_index', methods: ['GET'])]
    public function index(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement/index.html.twig', [
            'equipements' => $equipementRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_equipement_indexFront', methods: ['GET'])]
    public function indexFront(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement/indexFront.html.twig', [
            'equipements' => $equipementRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_equipement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $equipement = new Equipement();
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $equipement->setImage($newFilename);
            }

            // Generate QR Code
            $qrCode = new QrCode($equipement->getId() . '-' . $newFilename);
            $qrCode->setSize(300);

            // Convert QR Code to Base64
            $qrOutputInterface = new PngWriter();
            $qrCodeString = $qrOutputInterface->write($qrCode)->getString();
            $qrCodeBase64 = base64_encode($qrCodeString);

            // Save only the Base64 string to the database (without data URI schema)
            $equipement->setQrCode($qrCodeBase64);

            $entityManager->persist($equipement);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipement/new.html.twig', [
            'equipement' => $equipement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipement_show', methods: ['GET'])]
    public function show(Equipement $equipement): Response
    {
        return $this->render('equipement/show.html.twig', [
            'equipement' => $equipement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipement/edit.html.twig', [
            'equipement' => $equipement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipement_delete', methods: ['POST'])]
    public function delete(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $equipement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipement_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/generate-pdf1', name: 'generate_pdf1', methods: ['GET'])]
    public function generatePdf1(EquipementRepository $equipementRepository): Response
    {
        // Récupérer tous les équipements depuis le référentiel
        $equipements = $equipementRepository->findAll();

        // Générer le contenu HTML du tableau
        $html = '<h1 style="color: red;">Liste des équipements disponibles</h1>';
        $html .= '<table border="1" style="width: 100%;">';
        $html .= '<tr><th>Nom</th><th>Nombre</th></tr>';
        foreach ($equipements as $equipement) {
            $html .= '<tr>';
            $html .= '<td>' . $equipement->getNom() . '</td>';
            $html .= '<td>' . $equipement->getNombre() . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        // Créer une instance de Dompdf
        $dompdf = new Dompdf();

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // (Optionnel) Définir les options du PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf->setOptions($options);

        // Rendre le PDF
        $dompdf->render();

        // Envoyer le PDF au navigateur en tant que réponse
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    #[Route('/excel', name: 'generate_excel', methods: ['GET'])]
    public function generateExcel(EquipementRepository $equipementRepository): Response
    {
        // Récupérer tous les équipements depuis le référentiel
        $equipements = $equipementRepository->findAll();

        // Trouver le plus petit nombre parmi les équipements
        $minNombre = PHP_INT_MAX;
        foreach ($equipements as $equipement) {
            $nombre = $equipement->getNombre();
            if ($nombre < $minNombre) {
                $minNombre = $nombre;
            }
        }

        // Créer une nouvelle instance de Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Sélectionner la feuille de calcul active
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter le titre en rouge
        $sheet->setCellValue('A1', 'Liste des équipements disponibles');
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FF0000');

        // Ajouter des données à la feuille de calcul
        $sheet->setCellValue('A2', 'Nom');
        $sheet->setCellValue('B2', 'Nombre');
        // Ajoutez plus de colonnes au besoin...

        // Appliquer des styles aux en-têtes de colonnes
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
        ];
        $sheet->getStyle('A2:B2')->applyFromArray($headerStyle);

        // Ajouter les données des équipements à la feuille de calcul
        $row = 3;
        foreach ($equipements as $equipement) {
            $sheet->setCellValue('A' . $row, $equipement->getNom());
            $sheet->setCellValue('B' . $row, $equipement->getNombre());

            // Vérifier si le nombre est égal au plus petit nombre
            if ($equipement->getNombre() === $minNombre) {
                // Appliquer une couleur de remplissage à la cellule contenant le plus petit nombre
                $cellFill = $sheet->getStyle('B' . $row)->getFill();
                $cellFill->setFillType(Fill::FILL_SOLID);
                $cellFill->getStartColor()->setARGB('00FF00'); // Vert pour le plus petit nombre
            }

            // Ajoutez plus de colonnes au besoin...
            $row++;
        }

        // Créer un objet Writer pour sauvegarder le fichier Excel
        $writer = new Xlsx($spreadsheet);

        // Créer un chemin pour le fichier Excel
        $excelFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/equipements.xlsx';

        // Enregistrer le fichier Excel sur le serveur
        $writer->save($excelFilePath);

        // Créer une réponse pour télécharger le fichier Excel
        $response = new Response();
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="equipements.xlsx"');
        $response->setContent(file_get_contents($excelFilePath));

        // Supprimer le fichier Excel temporaire après l'avoir téléchargé
        unlink($excelFilePath);

        return $response;
    }


    #[Route('/rate', name: 'app_equipement_rate', methods: ['POST'])]
    public function rate(Request $request, EntityManagerInterface $entityManager, EquipementRepository $equipementRepository): JsonResponse
    {
        $equipementId = $request->request->get('id');
        $rating = $request->request->get('rating');

        $equipement = $equipementRepository->find($equipementId);
        if ($equipement) {
            $equipement->setRating($rating);
            $entityManager->flush();
            return new JsonResponse(['status' => 'success', 'message' => 'Rating updated']);
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Equipment not found'], 404);
    }


    #[Route('/equipement/search', name: 'app_equipement_search', methods: ['GET'])]
    public function search(Request $request, EquipementRepository $equipementRepository): Response
    {
        $searchTerm = $request->query->get('q');

        // Recherche dans les équipements en fonction du nom
        $equipement = $equipementRepository->findOneBy(['nom' => $searchTerm]);

        if ($equipement !== null) {
            // Redirige vers la page des détails de l'équipement
            return $this->redirectToRoute('app_equipement_show', ['id' => $equipement->getId()]);
        }

        // Si aucun équipement n'est trouvé, affiche une vue avec un message d'erreur
        return $this->render('equipement/not_found.html.twig', [
            'searchTerm' => $searchTerm,
        ]);
    }
}
