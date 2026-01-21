<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InterventionTypeController extends AbstractController
{
    #[Route('/intervention/type', name: 'app_index_interventionType')]
    public function index(): Response
    {
        return $this->render('intervention_type/index.html.twig', [
            'controller_name' => 'InterventionTypeController',
        ]);
    }
}
