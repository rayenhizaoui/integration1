<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\SearchEventType;
use App\Form\SearchTournoiType;
use App\Form\TournoiType;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tournoi')]
class TournoiController extends AbstractController
{
    #[Route('/', name: 'app_tournoi_index', methods: ['GET'])]
    public function index(TournoiRepository $tournoiRepository): Response
    {
        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
        ]);
    }
    #[Route('/pdf', name: 'app_tournoi_pdf', methods: ['GET'])]
    public function PDF(TournoiRepository $tournoiRepository): Response
    {
        return $this->render('tournoi/pdf.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
        ]);
    }
    #[Route('/Home', name: 'app_Home', methods: ['GET'])]
    public function HomePath(): Response
    {
        return $this->render('base.html.twig');
    }
    #[Route('/HomeFront', name: 'app_Home_Front', methods: ['GET'])]
    public function HomePathFront(TournoiRepository $tournoiRepository): Response
    {
        return $this->render('baseFront.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
        ] );
    }
    #[Route('/indexFront', name: 'index_Front', methods: ['GET'])]
    public function indexFront(TournoiRepository $tournoiRepository , Request $request): Response
    {
        $data = new SearchController();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchTournoiType::class, $data);
        $form->handleRequest($request);
        $tournois = $tournoiRepository->findSearch($data);
        return $this->render('tournoi/IndexFront.html.twig', [
            'tournois' => $tournois,
            'form' => $form->createView()
        ] );
    }


    #[Route('/new', name: 'app_tournoi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('image')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données

                $tournoi->setImage($fichier);
            }
            $entityManager->persist($tournoi);
            $entityManager->flush();

            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tournoi_show', methods: ['GET'])]
    public function show(Tournoi $tournoi): Response
    {
        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    #[Route('front/{id}', name: 'app_tournoi_show_front', methods: ['GET'])]
    public function showFront(Tournoi $tournoi): Response
    {
        return $this->render('tournoi/showFront.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }
    #[Route('front/Location/{id}', name: 'app_tournoi_show_location', methods: ['GET'])]
    public function showLocation(Tournoi $tournoi): Response
    {
        return $this->render('tournoi/map.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tournoi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('image')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données

                $tournoi->setImage($fichier);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournoi/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
    }
}
