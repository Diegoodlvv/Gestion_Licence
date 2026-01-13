<?php

namespace App\Controller;

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
        $start_date = $request->query->get('start_date');
        $end_date = $request->query->get('end_date');
        $module = $request->query->get('module');

        $interventions = $interventionRepository->findByFilters($start_date, $end_date, $module);

        $pagination = $paginationInterface->paginate(
            $interventions,
            $request->query->getInt('page', 1),
            10
        );

        $title = "Interventions";

        return $this->render('intervention/index.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }
}
