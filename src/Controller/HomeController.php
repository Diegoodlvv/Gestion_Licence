<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerForm;
use App\Repository\CustomerRepository;
use App\Repository\InterventionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
