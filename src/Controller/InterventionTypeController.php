<?php

namespace App\Controller;

use App\Form\InterventionTypeFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InterventionTypeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class InterventionTypeController extends AbstractController
{
    #[Route('/interventionType', name: 'app_index_interventionType')]
    public function index(PaginatorInterface $paginator, Request $request, EntityManagerInterface $em, InterventionTypeRepository $interventionTypeRepository): Response
    {

        $form = $this->createForm(InterventionTypeFilterType::class);
        $form->handleRequest($request);

        $interventionType = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();

            if ($name) {
                $interventionType = $interventionTypeRepository->findInstructorByName($name);
            } else {
                $interventionType = $interventionTypeRepository->findAll();
            }
        } else {
            $interventionType = $interventionTypeRepository->findAll();
        }

        //Pagination
        $interventionType = $paginator->paginate(
            $interventionType,
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('intervention_type/index.html.twig', [
            'interventionType' => $interventionType,
            'form' => $form,
        ]);
    }
}
