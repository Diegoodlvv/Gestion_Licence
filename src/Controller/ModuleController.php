<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ModuleRepository;
use App\Repository\TeachingBlockRepository;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(ModuleRepository $moduleRepository, TeachingBlockRepository $teachingBlockRepository): Response
    {

        $teachingBlocks = $teachingBlockRepository->findAll();
        $modules = $moduleRepository->findAll();



        return $this->render('module/index.html.twig', [
            'modules' => $modules,
            'teachingBlocks' => $teachingBlocks,
        ]);
    }
}
