<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TeachingBlockController extends AbstractController
{
    #[Route('/teachingBlock', name: 'app_teaching_block')]
    public function index(): Response
    {
        return $this->render('teaching_block/index.html.twig', [
            'controller_name' => 'TeachingBlockController',
        ]);
    }
}
