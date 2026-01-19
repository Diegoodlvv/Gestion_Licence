<?php

namespace App\Controller;

use App\Form\InterventionsFilterType;
use App\Repository\InterventionRepository;
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
}
