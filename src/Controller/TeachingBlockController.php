<?php

namespace App\Controller;

use App\Entity\TeachingBlock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\TeachingBlockFilterType;
use App\Form\TeachingBlockType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TeachingBlockRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/teachingBlock/{id}/edit', name: 'app_teaching_block_edit')]
    public function new(TeachingBlock $teachingBlock, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(TeachingBlockType::class, $teachingBlock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();

                $this->addFlash('success', 'Bloc d\'enseignement modifié avec succes !');
                return $this->redirectToRoute('app_teaching_block');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenur lors de l\'ajout du bloc d\'enseignement');
                return $this->redirectToRoute('app_teaching_block');
            }
        };

        return $this->render('teaching_block/edit.html.twig', [
            'form' => $form,
            'teachingBlock' => $teachingBlock,
        ]);
    }
}
