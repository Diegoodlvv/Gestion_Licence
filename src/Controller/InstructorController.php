<?php

namespace App\Controller;

use App\Entity\Instructor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\NewInstructorType;
use App\Form\InstructorFilterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\InstructorRepository;
use App\Form\EditInstructorType;
use App\Form\InstructorInterventionsFilterType;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Route("/instructor")]
class InstructorController extends AbstractController
{
    #[Route('/', name: 'app_instructor')]
    public function index(Request $request, InstructorRepository $instructorRepository, PaginatorInterface $pagination): Response
    {
        $form = $this->createForm(InstructorFilterType::class);
        $form->handleRequest($request);

        $instructors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $lastname = $form->get('lastname')->getData();
            $firstname = $form->get('firstname')->getData();
            $email = $form->get('email')->getData();

            if ($lastname || $firstname || $email) {
                $instructors = $instructorRepository->InstructorByFilters($lastname, $firstname, $email);
            } else {
                $instructors = $instructorRepository->findAll();
            }
        } else {
            $instructors = $instructorRepository->findAll();
        }

        $pagination = $pagination->paginate(
            $instructors,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('instructor/index.html.twig', [
            'instructors' => $pagination,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show_interventions', name: 'app_instructor_interventions')]
    public function showInterventions($id, InstructorRepository $instructorRepository, Request $request, PaginatorInterface $pagination): Response
    {
        $instructor = $instructorRepository->find($id);
        $form = $this->createForm(InstructorInterventionsFilterType::class);
        $form->handleRequest($request);

        $interventions = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $start_date = $form->get('start_date')->getData();
            $end_date = $form->get('end_date')->getData();
            $module = $form->get('module')->getData();

            if ($start_date || $end_date || $module) {
                $interventions = $instructorRepository->InstructorInterventionsByFilters($id, $start_date, $end_date, $module);
            }
        } else {
            $interventions = $instructorRepository->InstructorInterventionsByFilters($id, null, null, null);
        }

        $pagination = $pagination->paginate(
            $interventions,
            $request->query->getInt('page', 1),
            10
        );

        $usedHours = $instructorRepository->getUsedHoursPerModuleForInstructor($id);

        return $this->render('instructor/interventions.html.twig', [
            'interventions' => $pagination,
            'form' => $form,
            'instructor' => $instructor,
            'usedHours' => $usedHours,
        ]);
    }

    #[Route('/new', name: 'app_instructor_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $instructor = new Instructor();
        $form = $this->createForm(NewInstructorType::class, $instructor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // new user
                $user = new User();
                $user->setEmail($form->get('email')->getData());
                $user->setFirstname($form->get('firstname')->getData());
                $user->setLastname($form->get('lastname')->getData());
                $user->setRoles(['ROLE_USER']);

                // hash
                $password = $form->get('plainPassword')->getData();
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );

                $entityManager->persist($user);

                // lier l'utilisateur à l'instructeur
                $instructor->setUser($user);

                $entityManager->persist($instructor);
                $entityManager->flush();
                $this->addFlash('success', 'Enseignant ajouté avec succès !');
                return $this->redirectToRoute('app_instructor');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'enseignant');
                return $this->redirectToRoute('app_instructor');
            }
        }


        return $this->render('instructor/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_instructor_edit')]
    public function edit($id, InstructorRepository $instructorRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $instructor = $instructorRepository->find($id);
        $form = $this->createForm(EditInstructorType::class, $instructor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Enseignant modifié avec succès !');
                return $this->redirectToRoute('app_instructor');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'enseignant : ');
                return $this->redirectToRoute('app_instructor');
            }
        }

        $usedHours = $instructorRepository->getUsedHoursPerModuleForInstructor($id);

        return $this->render('instructor/edit.html.twig', [
            'form' => $form,
            'instructor' => $instructor,
            'usedHours' => $usedHours
        ]);
    }

    #[Route('/{id}/delete', name: 'app_instructor_delete')]
    public function delete($id, InstructorRepository $instructorRepository, EntityManagerInterface $entityManager)
    {
        $instructor = $instructorRepository->find($id);

        if ($instructor) {
            $entityManager->remove($instructor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_instructor');
    }

    #[Route('/instructor/{id}/export', name: 'app_instructor_export')]
    public function export($id, InstructorRepository $instructorRepository): StreamedResponse
    {
        $instructor = $instructorRepository->find($id);

        $interventionsQuery = $instructorRepository->InstructorInterventionsByFilters($id, null, null, null); // recuperation de toutes le interventions
        $interventions = $interventionsQuery->getQuery()->getResult(); // execute la requete sql pour obtenir la liste des objets

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(60);
        $sheet->getColumnDimension('E')->setWidth(15);

        $currentRow = 1;

        $interventionsByModule = [];
        foreach ($interventions as $intervention) {
            $module = $intervention->getModule();
            if (!$module) {
                continue;
            }
            $moduleName = $module->getName();
            if (!isset($interventionsByModule[$moduleName])) {
                $interventionsByModule[$moduleName] = [];
            }
            $interventionsByModule[$moduleName][] = $intervention;
        }

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $academicYear = $currentYear . '-' . $nextYear;

        $sheet->mergeCells('A' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('A' . $currentRow, 'RÉPARTITION DES INTERVENTIONS PAR INTERVENANT ' . $academicYear);
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $currentRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF9BC2E6');
        $currentRow += 2;

        foreach ($interventionsByModule as $moduleName => $moduleInterventions) {
            $module = $moduleInterventions[0]->getModule();
            $instructorInitials = strtoupper(substr($instructor->getUser()->getFirstname(), 0, 1)) . '. ' .
                strtoupper($instructor->getUser()->getLastname());

            $sheet->mergeCells('A' . $currentRow . ':E' . $currentRow);
            $sheet->setCellValue('A' . $currentRow, $instructorInitials . ' : ' . $moduleName);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $currentRow++;

            $headers = ['', '', '', '', ''];
            $sheet->fromArray($headers, null, 'A' . $currentRow);
            $currentRow++;

            usort($moduleInterventions, function ($a, $b) {
                return $a->getStartDate() <=> $b->getStartDate();
            });

            $totalHours = 0;

            foreach ($moduleInterventions as $intervention) {
                $startDate = $intervention->getStartDate();
                $endDate = $intervention->getEndDate();

                $dayOfWeek = $this->getDayOfWeek($startDate->format('N'));
                $dayMonth = $startDate->format('j') . '-' . $this->getMonth($startDate->format('n')) . '.';

                $startHour = (int)$startDate->format('H');
                $endHour = (int)$endDate->format('H');

                $timeSlot = '';
                if ($startHour < 12 && $endHour <= 13) {
                    $timeSlot = 'Matin';
                } elseif ($startHour >= 13) {
                    $timeSlot = 'Après-midi';
                } elseif ($startHour < 12 && $endHour > 13) {
                    $timeSlot = 'Journée';
                }

                $title = $intervention->getTitle() ?? '';

                $durationSeconds = $endDate->getTimestamp() - $startDate->getTimestamp();
                $hours = $durationSeconds / 3600;
                $totalHours += $hours;

                $sheet->setCellValue('A' . $currentRow, $dayOfWeek);
                $sheet->setCellValue('B' . $currentRow, $dayMonth);
                $sheet->setCellValue('C' . $currentRow, $timeSlot);
                $sheet->setCellValue('D' . $currentRow, $title);
                $sheet->setCellValue('E' . $currentRow, $hours);

                $currentRow++;
            }

            $currentRow++;

            $sheet->setCellValue('D' . $currentRow, 'Total heures');
            $sheet->setCellValue('E' . $currentRow, $totalHours);
            $sheet->getStyle('D' . $currentRow . ':E' . $currentRow)->getFont()->setBold(true);
            $currentRow++;

            $moduleHours = $module->getHoursCount();
            $remainingHours = $moduleHours - $totalHours;
            $sheet->setCellValue('D' . $currentRow, 'Heures restantes à effectuer');
            $sheet->setCellValue('E' . $currentRow, $remainingHours);
            $sheet->getStyle('D' . $currentRow . ':E' . $currentRow)->getFont()->setBold(true);
            $currentRow += 3;
        }

        $highestRow = $currentRow - 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A2:E' . $highestRow)->applyFromArray($styleArray);

        $filename = 'Recapitulatif_' . $instructor->getUser()->getLastname() . '_' .
            $instructor->getUser()->getFirstname() . '_' . $academicYear . '.xlsx';

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    private function getDayOfWeek(int $dayNumber): string
    {
        $days = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
        return $days[$dayNumber] ?? '';
    }

    private function getMonth(string $monthNumber): string
    {
        $months = [
            '1' => 'janv',
            '2' => 'févr',
            '3' => 'mars',
            '4' => 'avr',
            '5' => 'mai',
            '6' => 'juin',
            '7' => 'juil',
            '8' => 'août',
            '9' => 'sept',
            '10' => 'oct',
            '11' => 'nov',
            '12' => 'déc'
        ];
        return $months[$monthNumber] ?? '';
    }
}
