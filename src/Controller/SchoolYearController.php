<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SchoolYearRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SchoolYearType;
use App\Entity\SchoolYear;

final class SchoolYearController extends AbstractController
{
    #[Route('/schoolyear', name: 'app_school_year')]
    public function index(PaginatorInterface $paginator, Request $request, EntityManagerInterface $em): Response
    {
        $dql   = "SELECT a FROM App\Entity\SchoolYear a";

        $query = $em->createQuery($dql);

        // Pagination
        $schoolYears = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // page courante
            10 // nombre d’éléments par page
        );

        return $this->render('school_year/index.html.twig', [
            'schoolYears' => $schoolYears,
        ]);
    }

    #[Route('/schoolyear/new', name: 'app_school_year_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $year = new SchoolYear();
        $form = $this->createForm(SchoolYearType::class, $year);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($year);
                $entityManager->flush();

                $this->addFlash('success', 'Année scolaire ajoutée avec succès !');
                return $this->redirectToRoute('app_school_year');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'année scolaire : ' . $e->getMessage());
                return $this->redirectToRoute('app_school_year');
            }
        }


        return $this->render('school_year/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/schoolyear/{id}/edit', name: 'app_school_year_edit')]
    public function edit($id, SchoolYearRepository $schoolYearRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $schoolYearRepository->findByCourseYear($id);

        $year = $schoolYearRepository->find($id);
        $form = $this->createForm(SchoolYearType::class, $year);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Année scolaire modifiée avec succès !');
                return $this->redirectToRoute('app_school_year');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'année scolaire : ' . $e->getMessage());
                return $this->redirectToRoute('app_school_year');
            }
        }

        return $this->render('school_year/edit.html.twig', [
            'form' => $form,
            'year' => $year,
            'course' => $course
        ]);
    }
}
