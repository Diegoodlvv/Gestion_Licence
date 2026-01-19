<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SchoolYearRepository;

final class SchoolYearController extends AbstractController
{
    #[Route('/schoolyear', name: 'app_school_year')]
    public function index(SchoolYearRepository $schoolYearRepository): Response
    {
        $schoolYears = $schoolYearRepository->findAll();

        return $this->render('school_year/index.html.twig', [
            'schoolYears' => $schoolYears,
        ]);
    }
}
