<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SchoolYearRepository;
use App\Repository\CoursePeriodRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SchoolYearType;
use App\Entity\CoursePeriod;
use App\Form\CoursePeriodType;
use App\Entity\SchoolYear;

final class SchoolYearController extends AbstractController
{
    #[Route('/schoolyear', name: 'app_school_year')]
    public function index(PaginatorInterface $paginator, Request $request, EntityManagerInterface $em): Response
    {
        $dql   = "SELECT a FROM App\Entity\SchoolYear a";

        $query = $em->createQuery($dql);

        $schoolYears = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), 
            10 
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

    #[Route('/schoolyear/{id}/delete', name: 'app_school_year_delete')]
    public function delete($id, SchoolYearRepository $schoolYearRepository, EntityManagerInterface $entityManager)
    {
        $year = $schoolYearRepository->find($id);

        if ($year) {
            try {
                $entityManager->remove($year);
                $entityManager->flush();

                $this->addFlash('success', 'Année scolaire supprimée avec succès !');
                return $this->redirectToRoute('app_school_year');
            } catch (\Exception) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer une année qui a des semaines de cours liées');
                return $this->redirectToRoute('app_school_year');
            }
        }

        return $this->redirectToRoute('app_school_year');
    }

    // --------------- Gestion des Semaines --------------- //

    #[Route('/schoolyear/{id}/newWeek', name: 'app_school_year_newWeek')]
    public function newWeek($id, SchoolYearRepository $schoolYearRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $year = $schoolYearRepository->find($id);

        $week = new CoursePeriod();
        $week->setSchoolYearId($year);

        $form = $this->createForm(CoursePeriodType::class, $week);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($week);
                $entityManager->flush();

                $this->addFlash('success', 'Semaine de cours ajoutée avec succès !');
                return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de la semaine de cours');
                return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
            }
        }

        return $this->render('school_year/newWeek.html.twig', [
            'form' => $form,
            'year' => $year,
        ]);
    }

    #[Route('/schoolyear/{id}/Week/{weekId}/edit', name: 'app_school_year_editWeek')]
    public function editWeek($id, $weekId, SchoolYearRepository $schoolYearRepository, CoursePeriodRepository $coursePeriodRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $year = $schoolYearRepository->find($id);

        $week = $coursePeriodRepository->find($weekId);

        $form = $this->createForm(CoursePeriodType::class, $week);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Semaine modifiée avec succès !');
                return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de la semaine');
                return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
            }
        }

        return $this->render('school_year/editWeek.html.twig', [
            'form' => $form,
            'year' => $year,
            'week' => $week,
        ]);
    }

    #[Route('/schoolyear/{id}/week/{weekId}/delete', name: 'app_school_year_deleteWeek')]
    public function deleteWeek($id, $weekId, CoursePeriodRepository $coursePeriodRepository, EntityManagerInterface $entityManager): Response
    {
        $week = $coursePeriodRepository->find($weekId);

        if (!$week) {
            $this->addFlash('error', 'Semaine de cours introuvable !');
            return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
        }

        // verifie si ya des intervention liees a la semaine
        $interventions = $week->getInterventions();

        if ($interventions->count() > 0) {
            $count = $interventions->count();
            $this->addFlash('error', "Vous ne pouvez pas supprimer cette semaine car elle est liée à {$count} intervention(s). Veuillez d'abord supprimer ou déplacer ces interventions.");
            return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
        }

        try {
            $entityManager->remove($week);
            $entityManager->flush();

            $this->addFlash('success', 'Semaine supprimée avec succès !');
            return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
        } catch (\Exception) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression de la semaine : ');
            return $this->redirectToRoute('app_school_year_edit', ['id' => $id]);
        }
    }
}
