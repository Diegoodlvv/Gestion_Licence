<?php

namespace App\Controller;

use App\Repository\InterventionRepository;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route(path:'/api/calendar' ,name: 'app_data_calendar', methods: ['GET'])]
    public function data(InterventionRepository $interventionRepository, Request $request): JsonResponse
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');

        $start = new DateTime($start);
        $end = new DateTime($end);

        $interventions = $interventionRepository->interventionByDate($start, $end);

        $data = [];

        foreach($interventions as $intervention){
           $intervenants = [];
            foreach($intervention->getInstructors() as $instructor){
                $intervenants[] = [
                    'nom' => $instructor->getUser()->getFirstname() . ' ' . $instructor->getUser()->getLastname(), 
                ];
            }

            $color = $intervention->getInterventionType()->getColor();

            $data[] = [
                'id' => $intervention->getId(),
                'title' => $intervention->getTitle(),
                'start' => $intervention->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $intervention->getEndDate()->format('Y-m-d H:i:s'),
                'backgroundColor' => $color .'1A',     
                'borderColor' => $color,
                'textColor' => $color,
                'intervenants' => $intervenants,
                'remotely' => $intervention->isRemotely(),
                'interventionType' => $intervention->getInterventionType()->getName()
            ];
        }

        return $this->json($data);
    }

    #[Route(path:'home' ,name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route(path: '/calendar/export/week', name: 'app_calendar_export_week', methods: ['GET'])]
    public function exportWeek(Request $request, InterventionRepository $interventionRepository): Response
    {
        $weekStart = $request->query->get('start');
        
        if (!$weekStart) {
            $weekStart = (new \DateTime())->modify('monday this week')->format('Y-m-d');
        }
        
        $startDate = new \DateTime($weekStart);
        $endDate = (clone $startDate)->modify('+4 days'); 
        
        $interventions = $interventionRepository->createQueryBuilder('i')
            ->where('i.start_date >= :start')
            ->andWhere('i.start_date <= :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate->modify('+1 day'))
            ->orderBy('i.start_date', 'ASC')
            ->getQuery()
            ->getResult();
        
        $spreadsheet = $this->WeekSpreadsheet($interventions, $startDate);
        
        $writer = new Xlsx($spreadsheet);
        $fileName = sprintf('Planning_Semaine_%s.xlsx', $startDate->format('d-m-Y'));
        
        $tempFile = tempnam(sys_get_temp_dir(), 'calendar_export');
        $writer->save($tempFile);
        
        $response = $this->file($tempFile, $fileName);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        return $response;
    }
    
    #[Route(path: '/calendar/export/year', name: 'app_calendar_export_year', methods: ['GET'])]
    public function exportYear(Request $request, InterventionRepository $interventionRepository): Response
    {
        $year = $request->query->get('year', date('Y'));
        
        $startDate = new \DateTime("$year-01-01");
        $endDate = new \DateTime("$year-12-31");
        
        $interventions = $interventionRepository->createQueryBuilder('i')
            ->where('i.start_date >= :start')
            ->andWhere('i.start_date <= :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('i.start_date', 'ASC')
            ->getQuery()
            ->getResult();
        
        if (empty($interventions)) {
            throw $this->createNotFoundException("Aucune intervention trouvée pour l'année $year");
        }
        
        $spreadsheet = $this->YearSpreadsheet($interventions, $year);
        
        $writer = new Xlsx($spreadsheet);
        $fileName = sprintf('Planning_Annuel_%s.xlsx', $year);
        
        $tempFile = tempnam(sys_get_temp_dir(), 'calendar_export');
        $writer->save($tempFile);
        
        $response = $this->file($tempFile, $fileName);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        return $response;
    }
    
    private function WeekSpreadsheet(array $interventions, \DateTime $weekStart): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $weekEnd = (clone $weekStart)->modify('+4 days');
        $sheet->setTitle('Semaine');
        
        $sheet->setCellValue('A1', sprintf('Semaine du %s au %s', 
            $weekStart->format('d/m/Y'), 
            $weekEnd->format('d/m/Y')
        ));
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        
        $headers = ['Jour', 'MATIN - Intervenant', 'MATIN - Titre', 'APRÈS-MIDI - Intervenant', 'APRÈS-MIDI - Titre'];
        $sheet->fromArray($headers, null, 'A3');
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);

        $interventionsByDay = $this->groupInterventionsByDay($interventions);
        
        $row = 4;
        $currentDate = clone $weekStart;
        
        for ($i = 0; $i < 5; $i++) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayName = $this->getFrenchDayName($currentDate) . ' ' . $currentDate->format('d/m');
            
            $sheet->setCellValue("A$row", $dayName);
            
            if (isset($interventionsByDay[$dateStr])) {
                $dayInterventions = $interventionsByDay[$dateStr];
                
                $morning = array_filter($dayInterventions, fn($i) => $i->getStartDate()->format('H') < 13);
                $afternoon = array_filter($dayInterventions, fn($i) => $i->getStartDate()->format('H') >= 13);
                
                if (!empty($morning)) {
                    $intervention = reset($morning);
                    $sheet->setCellValue("B$row", $this->getInstructorNames($intervention));
                    $sheet->setCellValue("C$row", $intervention->getTitle());
                }
                
                if (!empty($afternoon)) {
                    $intervention = reset($afternoon);
                    $sheet->setCellValue("D$row", $this->getInstructorNames($intervention));
                    $sheet->setCellValue("E$row", $intervention->getTitle());
                }
            }
            
            $row++;
            $currentDate->modify('+1 day');
        }
        
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(35);
        
        return $spreadsheet;
    }
    
    private function YearSpreadsheet(array $interventions, int $year): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle("Planning $year");
        
        $sheet->setCellValue('A1', "Planning Annuel $year");
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        
        $headers = ['Date', 'Jour', 'Heure', 'Intervenant(s)', 'Titre'];
        $sheet->fromArray($headers, null, 'A3');
        $sheet->getStyle('A3:E3')->getFont()->setBold(true);
        
        $row = 4;
        
        foreach ($interventions as $intervention) {
            $date = $intervention->getStartDate();
            
            $sheet->setCellValue("A$row", $date->format('d/m/Y'));
            $sheet->setCellValue("B$row", $this->getFrenchDayName($date));
            $sheet->setCellValue("C$row", $date->format('H:i') . ' - ' . $intervention->getEndDate()->format('H:i'));
            $sheet->setCellValue("D$row", $this->getInstructorNames($intervention));
            $sheet->setCellValue("E$row", $intervention->getTitle());
            
            $row++;
        }
        
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(40);
        
        return $spreadsheet;
    }
    
    private function groupInterventionsByDay(array $interventions): array
    {
        $grouped = [];
        foreach ($interventions as $intervention) {
            $dateKey = $intervention->getStartDate()->format('Y-m-d');
            if (!isset($grouped[$dateKey])) {
                $grouped[$dateKey] = [];
            }
            $grouped[$dateKey][] = $intervention;
        }
        return $grouped;
    }
    
    private function getInstructorNames($intervention): string
    {
        $names = [];
        foreach ($intervention->getInstructors() as $instructor) {
            $user = $instructor->getUser();
            $names[] = strtoupper(substr($user->getFirstname(), 0, 1)) . '. ' . strtoupper($user->getLastname());
        }
        return implode(', ', $names);
    }
    
    private function getFrenchDayName(\DateTime $date): string
    {
        $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        return $days[(int)$date->format('w')];
    }
}