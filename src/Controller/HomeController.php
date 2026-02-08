<?php

namespace App\Controller;

use App\Repository\InterventionRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route(path:'/api/calendar' ,name: 'app_data_calendar', methods: ['GET'])]
    public function data(InterventionRepository $interventionRepository): JsonResponse
    {
        $interventions = $interventionRepository->findAll();

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
        $endDate = (clone $startDate)->modify('+4 days'); // Lundi à Vendredi
        
        $interventions = $interventionRepository->createQueryBuilder('i')
            ->where('i.start_date >= :start')
            ->andWhere('i.start_date <= :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate->modify('+1 day'))
            ->orderBy('i.start_date', 'ASC')
            ->getQuery()
            ->getResult();
        
        $spreadsheet = $this->createWeekSpreadsheet($interventions, $startDate);
        
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
        
        $spreadsheet = $this->createYearSpreadsheet($interventions, $year);
        
        $writer = new Xlsx($spreadsheet);
        $fileName = sprintf('Planning_Annuel_%s.xlsx', $year);
        
        $tempFile = tempnam(sys_get_temp_dir(), 'calendar_export');
        $writer->save($tempFile);
        
        $response = $this->file($tempFile, $fileName);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        return $response;
    }
    
    private function createWeekSpreadsheet(array $interventions, \DateTime $weekStart): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $weekEnd = (clone $weekStart)->modify('+4 days');
        $sheet->setTitle('Semaine du ' . $weekStart->format('d/m/Y'));
        
        $sheet->setCellValue('A1', sprintf('Semaine du %s au %s', 
            $weekStart->format('d/m/Y'), 
            $weekEnd->format('d/m/Y')
        ));
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $legendRow = 3;
        $uniqueTypes = [];
        foreach ($interventions as $intervention) {
            $typeName = $intervention->getInterventionType()->getName();
            if (!isset($uniqueTypes[$typeName])) {
                $uniqueTypes[$typeName] = $intervention->getInterventionType()->getColor();
            }
        }
        
        // Afficher la légende
        foreach ($uniqueTypes as $typeName => $color) {
            $sheet->setCellValue("A$legendRow", $typeName);
            $hexColor = ltrim($color, '#');
            $sheet->getStyle("A$legendRow")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($hexColor);
            $legendRow++;
        }
        
        $headerRow = $legendRow + 1;
        $headers = ['', 'MATIN (8h30 - 12h00)', '', 'APRÈS-MIDI (13H30 - 17H00)', ''];
        $subHeaders = ['', 'INTERVENANT', 'OBSERVATIONS', 'INTERVENANT', 'OBSERVATIONS'];
        
        $sheet->fromArray($headers, null, "A$headerRow");
        $headerRow++;
        $sheet->fromArray($subHeaders, null, "A$headerRow");
        
        // Style des en-têtes
        $sheet->mergeCells("B" . ($headerRow - 1) . ":C" . ($headerRow - 1));
        $sheet->mergeCells("D" . ($headerRow - 1) . ":E" . ($headerRow - 1));
        
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle("A" . ($headerRow - 1) . ":E$headerRow")->applyFromArray($headerStyle);

        $interventionsByDay = $this->groupInterventionsByDay($interventions);
        
        $dataRow = $headerRow + 1;
        $currentDate = clone $weekStart;
        
        for ($i = 0; $i < 5; $i++) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayName = $this->getFrenchDayName($currentDate);
            
            $sheet->setCellValue("A$dataRow", $dayName);
            
            if (isset($interventionsByDay[$dateStr])) {
                $dayInterventions = $interventionsByDay[$dateStr];
                
                $morning = array_filter($dayInterventions, fn($i) => $i->getStartDate()->format('H') < 13);
                $afternoon = array_filter($dayInterventions, fn($i) => $i->getStartDate()->format('H') >= 13);
                
                if (!empty($morning)) {
                    $intervention = reset($morning);
                    $instructors = $this->getInstructorNames($intervention);
                    $sheet->setCellValue("B$dataRow", $instructors);
                    $sheet->setCellValue("C$dataRow", $intervention->getTitle());
                    
                    $color = $intervention->getInterventionType()->getColor();
                    $hexColor = ltrim($color, '#');
                    $sheet->getStyle("B$dataRow:C$dataRow")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB($hexColor);
                }
                
                if (!empty($afternoon)) {
                    $intervention = reset($afternoon);
                    $instructors = $this->getInstructorNames($intervention);
                    $sheet->setCellValue("D$dataRow", $instructors);
                    $sheet->setCellValue("E$dataRow", $intervention->getTitle());
                    
                    $color = $intervention->getInterventionType()->getColor();
                    $hexColor = ltrim($color, '#');
                    $sheet->getStyle("D$dataRow:E$dataRow")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB($hexColor);
                }
            }
            
            $sheet->getStyle("A$dataRow:E$dataRow")->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            
            $dataRow++;
            $currentDate->modify('+1 day');
        }
        
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(40);
        
        return $spreadsheet;
    }
    
    private function createYearSpreadsheet(array $interventions, int $year): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        
        $interventionsByWeek = $this->groupInterventionsByWeek($interventions, $year);
        
        $firstSheet = true;
        
        foreach ($interventionsByWeek as $weekNum => $weekData) {
            if ($firstSheet) {
                $sheet = $spreadsheet->getActiveSheet();
                $firstSheet = false;
            } else {
                $sheet = $spreadsheet->createSheet();
            }
            
            $weekStart = $weekData['start'];
            $weekEnd = $weekData['end'];
            
            $sheet->setTitle("Semaine $weekNum");
            
            $sheet->setCellValue('A1', sprintf('Semaine du %s au %s', 
                $weekStart->format('d/m/Y'), 
                $weekEnd->format('d/m/Y')
            ));
            $sheet->mergeCells('A1:G1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $headerRow = 5;
            $headers = ['', 'MATIN (8h30 - 12h00)', '', 'APRÈS-MIDI (13H30 - 17H00)', ''];
            $subHeaders = ['', 'INTERVENANT', 'OBSERVATIONS', 'INTERVENANT', 'OBSERVATIONS'];
            
            $sheet->fromArray($headers, null, "A$headerRow");
            $headerRow++;
            $sheet->fromArray($subHeaders, null, "A$headerRow");
            
            $sheet->mergeCells("B" . ($headerRow - 1) . ":C" . ($headerRow - 1));
            $sheet->mergeCells("D" . ($headerRow - 1) . ":E" . ($headerRow - 1));
            
            $headerStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ];
            $sheet->getStyle("A" . ($headerRow - 1) . ":E$headerRow")->applyFromArray($headerStyle);
            
            $interventionsByDay = $this->groupInterventionsByDay($weekData['interventions']);
            
            $dataRow = $headerRow + 1;
            $currentDate = clone $weekStart;
            
            for ($i = 0; $i < 5; $i++) {
                $dateStr = $currentDate->format('Y-m-d');
                $dayName = $this->getFrenchDayName($currentDate);
                
                $sheet->setCellValue("A$dataRow", $dayName);
                
                if (isset($interventionsByDay[$dateStr])) {
                    $dayInterventions = $interventionsByDay[$dateStr];
                    
                    $morning = array_filter($dayInterventions, fn($i) => $i->getStartDate()->format('H') < 13);
                    $afternoon = array_filter($dayInterventions, fn($i) => $i->getStartDate()->format('H') >= 13);
                    
                    if (!empty($morning)) {
                        $intervention = reset($morning);
                        $instructors = $this->getInstructorNames($intervention);
                        $sheet->setCellValue("B$dataRow", $instructors);
                        $sheet->setCellValue("C$dataRow", $intervention->getTitle());
                        
                        $color = $intervention->getInterventionType()->getColor();
                        $hexColor = ltrim($color, '#');
                        $sheet->getStyle("B$dataRow:C$dataRow")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB($hexColor);
                    }
                    
                    if (!empty($afternoon)) {
                        $intervention = reset($afternoon);
                        $instructors = $this->getInstructorNames($intervention);
                        $sheet->setCellValue("D$dataRow", $instructors);
                        $sheet->setCellValue("E$dataRow", $intervention->getTitle());
                        
                        $color = $intervention->getInterventionType()->getColor();
                        $hexColor = ltrim($color, '#');
                        $sheet->getStyle("D$dataRow:E$dataRow")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB($hexColor);
                    }
                }
                
                $sheet->getStyle("A$dataRow:E$dataRow")->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                
                $dataRow++;
                $currentDate->modify('+1 day');
            }
            
            $sheet->getColumnDimension('A')->setWidth(12);
            $sheet->getColumnDimension('B')->setWidth(50);
            $sheet->getColumnDimension('C')->setWidth(60);
            $sheet->getColumnDimension('D')->setWidth(50);
            $sheet->getColumnDimension('E')->setWidth(60);
        }
        
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
    
    private function groupInterventionsByWeek(array $interventions, int $year): array
    {
        if (empty($interventions)) {
            return [];
        }
        
        $weeks = [];
        
        foreach ($interventions as $intervention) {
            $date = $intervention->getStartDate();
            $weekNum = (int) $date->format('W'); 
            $weekYear = (int) $date->format('o'); 
            
            if ($weekYear != $year) {
                continue;
            }
            
            if (!isset($weeks[$weekNum])) {
                $weekStart = new \DateTime();
                $weekStart->setISODate($weekYear, $weekNum, 1); 
                $weekEnd = clone $weekStart;
                $weekEnd->modify('+4 days'); 
                
                $weeks[$weekNum] = [
                    'start' => $weekStart,
                    'end' => $weekEnd,
                    'interventions' => []
                ];
            }
            
            $weeks[$weekNum]['interventions'][] = $intervention;
        }
        
        ksort($weeks);
        
        return $weeks;
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