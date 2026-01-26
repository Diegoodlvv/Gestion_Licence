<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\TeachingBlockFilterType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TeachingBlockRepository;
use Knp\Component\Pager\PaginatorInterface;

final class TeachingBlockController extends AbstractController
{
    #[Route('/teachingBlock', name: 'app_teaching_block')]
    public function index(Request $request, TeachingBlockRepository $teachingBlockRepository, PaginatorInterface $pagination): Response
    {
        $form = $this->createForm(TeachingBlockFilterType::class);
        $form->handleRequest($request);

        $teachingBlock = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get('code')->getData();
            $name = $form->get('name')->getData();

            if ($code || $name) {
                $teachingBlock = $teachingBlockRepository->teachingBlockByFilters($code, $name);
            } else {
                $teachingBlock = $teachingBlockRepository->findAll();
            }
        } else {
            $teachingBlock = $teachingBlockRepository->findAll();
        }

        $pagination = $pagination->paginate(
            $teachingBlock,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('teaching_block/index.html.twig', [
            'teachingBlock' => $pagination,
            'form' => $form,
        ]);
    }
}
