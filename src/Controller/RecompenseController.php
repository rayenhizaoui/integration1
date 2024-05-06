<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Entity\Recompense;
use App\Form\RecompenseType;
use App\Repository\RecompenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/recompense')]
class RecompenseController extends AbstractController
{
    #[Route('/', name: 'app_recompense_index', methods: ['GET'])]
    public function index(Request $request, RecompenseRepository $recompenseRepository): Response
    {
        $page = $request->query->getInt('page', 1); // Récupérer le numéro de page depuis la requête GET, par défaut 1
        $limit = 4; // Limite d'éléments par page

        // Utiliser la méthode findAllPaginated pour récupérer les récompenses paginées
        $pagination = $recompenseRepository->findAllPaginated($page, $limit);

        return $this->render('recompense/index.html.twig', [
            'pagination' => $pagination, // Passer l'objet PaginationInterface à la vue
        ]);
    }

    #[Route('/new', name: 'app_recompense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recompense = new Recompense();
        $form = $this->createForm(RecompenseType::class, $recompense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recompense);
            $entityManager->flush(); // Flush here to get the ID

            $historique = new Historique();
            $historique->setOperationType('create');
            $historique->setDescription('created recompense: ' . $recompense->getId());
            $historique->setOperationDate(new \DateTime());
            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('app_recompense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recompense/new.html.twig', [
            'recompense' => $recompense,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_recompense_show', methods: ['GET'])]
    public function show(Recompense $recompense): Response
    {
        return $this->render('recompense/show.html.twig', [
            'recompense' => $recompense,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_recompense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recompense $recompense, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecompenseType::class, $recompense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Flush updated data

            $historique = new Historique();
            $historique->setOperationType('update');
            $historique->setDescription('updated recompense: ' . $recompense->getId());
            $historique->setOperationDate(new \DateTime());
            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('app_recompense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recompense/edit.html.twig', [
            'recompense' => $recompense,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_recompense_delete', methods: ['POST'])]
    public function delete(Request $request, Recompense $recompense, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recompense->getId(), $request->request->get('_token'))) {
            $historique = new Historique();
            $historique->setOperationType('delete');
            $historique->setDescription('deleted recompense: ' . $recompense->getId());
            $historique->setOperationDate(new \DateTime());
            $entityManager->persist($historique);

            $entityManager->remove($recompense);
            $entityManager->flush();

            return $this->redirectToRoute('app_recompense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_recompense_index');
    }

  
}

    
 