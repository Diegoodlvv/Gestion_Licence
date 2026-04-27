<?php

namespace App\Controller;

use App\Form\Filter\InterventionTypeFilterType;
use App\Form\InterventionTypeEditType;
use App\Repository\InterventionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InterventionTypeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\InterventionType;
use PhpParser\Node\Stmt\TryCatch;

final class InterventionTypeController extends AbstractController
{
    #[Route('/interventionType', name: 'app_interventiontype', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request, InterventionTypeRepository $interventionTypeRepository): Response
    {

        $form = $this->createForm(InterventionTypeFilterType::class);
        $form->handleRequest($request);

        $name = $form->get('name')->getData();

        $interventionType = $interventionTypeRepository->findInterventionTypeByName($name);

        if ($form->isSubmitted()) {
                if ($form->isValid()) {
                $interventionType = $interventionTypeRepository->findInterventionTypeByName($name);
            }
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

    #[Route('/interventionType/new', name: 'app_interventiontype_new', methods: ['POST'])]
    public function new(Request $request, InterventionTypeRepository $interventionTypeRepository, EntityManagerInterface $em)
    {
        $interventionType = new InterventionType();

        $form = $this->createForm(InterventionTypeEditType::class, $interventionType);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()){
                try {
                    $em->persist($interventionType);
                    $em->flush();

                    $this->addFlash('success', 'Type d\'intervention ajoute avec succès !');
                    return $this->redirectToRoute('app_interventiontype');
                } catch (\Exception) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout du type d\'intervention');
                    return $this->redirectToRoute('app_interventiontype');
                }
            } else {
                $this->addFlash('error', 'Erreur avec le formulaire');
                return $this->redirectToRoute('app_interventiontype');
            }
        }

        return $this->render('intervention_type/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/interventionType/{id}/edit', name: 'app_interventiontype_edit')]
    public function edit($id, Request $request, InterventionTypeRepository $interventionTypeRepository, EntityManagerInterface $em): Response
    {
        $interventionType = $interventionTypeRepository->find($id);
        $form = $this->createForm(InterventionTypeEditType::class, $interventionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();
                $this->addFlash('success', 'Type d\'intervention modifie avec succes !');
                return $this->redirectToRoute('app_interventiontype');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification du type d\'intervention');
                return $this->redirectToRoute('app_interventiontype');
            }
        }


        return $this->render('intervention_type/edit.html.twig', [
            'form' => $form,
            'interventionType' => $interventionType,
        ]);
    }

    #[Route('/interventionType/{id}/delete', name: 'app_interventiontype_delete')]
    public function delete($id, InterventionTypeRepository $interventionTypeRepository, EntityManagerInterface $em): Response
    {
        $interventionType = $interventionTypeRepository->find($id);

        if ($interventionType) {
            try {
                $em->remove($interventionType);
                $em->flush();

                $this->addFlash('success', 'Type d\'intervention supprimée avec succès !');
                return $this->redirectToRoute('app_interventiontype');
            } catch (\Exception) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer un type d\'intervention qui a des interventions liés');
                return $this->redirectToRoute('app_interventiontype');
            }
        }

        return $this->redirectToRoute('app_interventiontype');
    }
}
