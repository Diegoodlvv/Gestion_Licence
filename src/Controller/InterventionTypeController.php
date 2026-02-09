<?php

namespace App\Controller;

use App\Entity\InterventionType;
use App\Form\filter\InterventionTypeFilterType;
use App\Form\InterventionTypeEditType;
use App\Repository\InterventionTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InterventionTypeController extends AbstractController
{
    #[Route('/interventionType', name: 'app_interventiontype')]
    public function index(PaginatorInterface $paginator, Request $request, EntityManagerInterface $em, InterventionTypeRepository $interventionTypeRepository): Response
    {
        $form = $this->createForm(InterventionTypeFilterType::class);
        $form->handleRequest($request);

        $interventionType = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();

            if ($name) {
                $interventionType = $interventionTypeRepository->findInstructorByName($name);
            } else {
                $interventionType = $interventionTypeRepository->findAll();
            }
        } else {
            $interventionType = $interventionTypeRepository->findAll();
        }

        $interventionType = $paginator->paginate(
            $interventionType,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('intervention_type/index.html.twig', [
            'interventionType' => $interventionType,
            'form' => $form,
        ]);
    }

    #[Route('/interventionType/new', name: 'app_interventionType_new')]
    public function new(Request $request, InterventionTypeRepository $interventionTypeRepository, EntityManagerInterface $em)
    {
        $interventionType = new InterventionType();

        $form = $this->createForm(InterventionTypeEditType::class, $interventionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($interventionType);
                $em->flush();

                $this->addFlash('success', 'Type d\'intervention ajoute avec succès !');

                return $this->redirectToRoute('app_index_interventionType');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout du type d\'intervention');

                return $this->redirectToRoute('app_index_interventionType');
            }
        }

        return $this->render('intervention_type/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/interventionType/{id}/edit', name: 'app_interventionType_edit')]
    public function edit(InterventionType $interventionType, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(InterventionTypeEditType::class, $interventionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();
                $this->addFlash('success', 'Type d\'intervention modifie avec succes !');

                return $this->redirectToRoute('app_index_interventionType');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification du type d\'intervention');

                return $this->redirectToRoute('app_index_interventionType');
            }
        }

        return $this->render('intervention_type/edit.html.twig', [
            'form' => $form,
            'interventionType' => $interventionType,
        ]);
    }

    #[Route('/interventionType/{id}/delete', name: 'app_interventionType_delete')]
    public function delete(InterventionType $interventionType, EntityManagerInterface $em): Response
    {
        if ($interventionType) {
            try {
                $em->remove($interventionType);
                $em->flush();

                $this->addFlash('success', 'Type d\'intervention supprimée avec succès !');

                return $this->redirectToRoute('app_index_interventionType');
            } catch (\Exception) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer un type d\'intervention qui a des interventions liés');

                return $this->redirectToRoute('app_index_interventionType');
            }
        }

        return $this->redirectToRoute('app_index_interventionType');
    }
}
