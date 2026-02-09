<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ModuleRepository;
use App\Repository\TeachingBlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ModuleType;
use App\Entity\Module;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(ModuleRepository $moduleRepository, TeachingBlockRepository $teachingBlockRepository): Response
    {
        $teachingBlocks = $teachingBlockRepository->findAll();

        $modulesByBlock = [];
        foreach ($teachingBlocks as $teachingBlock) {
            $modulesByBlock[$teachingBlock->getId()] = [
                'block' => $teachingBlock,
                'modules' => []
            ];
        }

        $topLevelModules = $moduleRepository->findModulesGroupedByTeachingBlock();

        foreach ($topLevelModules as $module) {
            $blockId = $module->getTeachingBlock()->getId();

            $modulesByBlock[$blockId]['modules'][] = $module;
        }



        return $this->render('module/index.html.twig', [
            'modulesByBlock' => $modulesByBlock,
        ]);
    }


    #[Route('/module/{id}/add', name: 'app_module_add')]
    public function add($id, Request $request, EntityManagerInterface $em, TeachingBlockRepository $teachingBlockRepository): Response
    {
        $teachingBlock = $teachingBlockRepository->find($id);

        $module = new Module();
        $module->setTeachingBlock($teachingBlock); // tout module a un TB,  donc on lui met celui qui est selectionne

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($module);
                $em->flush();

                $this->addFlash('success', 'Module ajoute avec succes!');
                return $this->redirectToRoute('app_module');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenur lors de l\'ajout du module');
                return $this->redirectToRoute('app_module');
            }
        }


        return $this->render('module/add.html.twig', [
            'form' => $form,
            'teachingBlock' => $teachingBlock
        ]);
    }

    // dans add il faut faire $module->setTeachingBlock($teachingBlock); car le module ne connais pas son TB lors de la creation
    // donc on lui en assimile un
    // dans edit, il a deja un TB, donc pas besoin de $module->setTeachingBlock($teachingBlock);
    // Puis ensuite le formulaire affiche le TB avec $value = $teachingBlock->getCode() . ' - ' . $teachingBlock->getName();
    // la difference est entre ce que represente $id passe en parametre

    #[Route('/module/{id}/edit', name: 'app_module_edit')]
    public function edit(Module $module, Request $request, ModuleRepository $moduleRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();
                $this->addFlash('success', 'Module modifié avec succès !');
                return $this->redirectToRoute('app_module');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenir lors de la modification du module !');
                return $this->redirectToRoute('app_module');
            }
        }

        return $this->render('module/edit.html.twig', [
            'form' => $form,
            'module' => $module
        ]);
    }

    #[Route('/module/{id}/delete', name: 'app_module_delete')]
    public function delete($id,  EntityManagerInterface $em, ModuleRepository $moduleRepository): Response
    {
        $module = $moduleRepository->find($id);

        if ($module) {
            $moduleName = $module->getName();
            try {
                $em->remove($module);
                $em->flush();
                $this->addFlash('success', 'Module supprimé avec succès.');
            } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException) {
                $this->addFlash('error', 'Impossible de supprimer le module "' . $moduleName . '" car il est lié à une ou plusieurs interventions.');
            } catch (\Exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression du module ');
            }
        }

        return $this->redirectToRoute('app_module');
    }
}
