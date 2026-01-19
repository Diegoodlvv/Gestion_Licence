<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Form\InterventionsFilterType;
use App\Form\NewInterventionType;
use App\Repository\InterventionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/intervention")]
final class InterventionController extends AbstractController
{
    #[Route(name: 'app_intervention', methods: ['GET'])]
    public function index(InterventionRepository $interventionRepository, Request $request, PaginatorInterface $paginationInterface): Response
    {
        $form = $this->createForm(InterventionsFilterType::class);
        $form->handleRequest($request);

        $interventions = []; 

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $form->get('start_date')->getData();
            $endDate = $form->get('end_date')->getData();
            $module = $form->get('module')->getData(); 

            $interventions = $interventionRepository->queryFilters($startDate, $endDate, $module);
        }  else {
            $interventions = $interventionRepository->findAll();
        }

        $pagination = $paginationInterface->paginate(
            $interventions,
            $request->query->getInt('page', 1),
            10
        );

        $title = "Interventions";

        return $this->render('intervention/index.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'form' => $form,
        ]);
    }

    #[Route('/intervention/new', name: 'app_intervention_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $intervention = new Intervention();
        $form = $this->createForm(NewInterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->addFlash('success', 'Intervention ajouté avec succès');
                $entityManager->persist($intervention);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'enseignant : ' . $e->getMessage());
                return $this->redirectToRoute('app_instructor');
            }
        }

        return $this->render('intervention/new.html.twig', [
            'form' => $form,
            'intervention' => $intervention
        ]);
    }
}
