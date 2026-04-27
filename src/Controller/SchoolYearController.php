<?php

namespace App\Controller;

use App\Entity\CoursePeriod;
use App\Entity\SchoolYear;
use App\Form\CoursePeriodType;
use App\Form\SchoolYearType;
use App\Repository\CoursePeriodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SchoolYearController extends AbstractController
{
    #[Route('/schoolyear', name: 'app_school_year')]
    public function index(PaginatorInterface $paginator, Request $request, EntityManagerInterface $em): Response
    {
        $dql = "SELECT a FROM App\Entity\SchoolYear a";

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
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'année scolaire : ');

                return $this->redirectToRoute('app_school_year');
            }
        }

        return $this->render('school_year/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/schoolyear/{id}/edit', name: 'app_school_year_edit')]
    public function edit(SchoolYear $schoolYear, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $schoolYear->getCoursePeriods();

        $form = $this->createForm(SchoolYearType::class, $schoolYear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Année scolaire modifiée avec succès !');

                return $this->redirectToRoute('app_school_year');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'année scolaire : '.$e->getMessage());

                return $this->redirectToRoute('app_school_year');
            }
        }

        return $this->render('school_year/edit.html.twig', [
            'form' => $form,
            'year' => $schoolYear,
            'course' => $course,
        ]);
    }

    #[Route('/schoolyear/{id}/delete', name: 'app_school_year_delete')]
    public function delete(SchoolYear $schoolYear, EntityManagerInterface $entityManager)
    {
        if ($schoolYear) {
            try {
                $entityManager->remove($schoolYear);
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
    public function newWeek(SchoolYear $schoolYear, Request $request, EntityManagerInterface $entityManager): Response
    {
        $week = new CoursePeriod();
        $week->setSchoolYear($schoolYear);

        $form = $this->createForm(CoursePeriodType::class, $week);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($week);
                $entityManager->flush();

                $this->addFlash('success', 'Semaine de cours ajoutée avec succès !');

                return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de la semaine de cours');

                return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
            }
        }

        return $this->render('school_year/newWeek.html.twig', [
            'form' => $form,
            'year' => $schoolYear,
        ]);
    }

    #[Route('/schoolyear/{id}/Week/{coursePeriod}/edit', name: 'app_school_year_editWeek')]
    public function editWeek(SchoolYear $schoolYear, CoursePeriod $coursePeriod, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoursePeriodType::class, $coursePeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Semaine modifiée avec succès !');

                return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de la semaine');

                return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
            }
        }

        return $this->render('school_year/editWeek.html.twig', [
            'form' => $form,
            'year' => $schoolYear,
            'week' => $coursePeriod,
        ]);
    }

    #[Route('/schoolyear/{id}/week/{coursePeriod}/delete', name: 'app_school_year_deleteWeek')]
    public function deleteWeek(SchoolYear $schoolYear, CoursePeriod $coursePeriod, CoursePeriodRepository $coursePeriodRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$coursePeriod) {
            $this->addFlash('error', 'Semaine de cours introuvable !');

            return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
        }

        $interventions = $coursePeriod->getInterventions();

        if ($interventions->count() > 0) {
            $count = $interventions->count();
            $this->addFlash('error', "Vous ne pouvez pas supprimer cette semaine car elle est liée à {$count} intervention(s). Veuillez d'abord supprimer ou déplacer ces interventions.");

            return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
        }

        try {
            $entityManager->remove($coursePeriod);
            $entityManager->flush();

            $this->addFlash('success', 'Semaine supprimée avec succès !');

            return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
        } catch (\Exception) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression de la semaine : ');

            return $this->redirectToRoute('app_school_year_edit', ['id' => $schoolYear->getId()]);
        }
    }
}
