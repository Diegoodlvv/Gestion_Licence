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

        // regroupe les modules et les Teachin blocks
        // creer tableau contenant: - un tableau avec informations des blocks
        //                          - un tableau module, vide pour l'instant
        $modulesByBlock = [];
        foreach ($teachingBlocks as $teachingBlock) {
            $modulesByBlock[$teachingBlock->getId()] = [
                'block' => $teachingBlock,
                'modules' => []
            ];
        }

        // recuperation des modules parents
        $topLevelModules = $moduleRepository->findModulesGroupedByTeachingBlock();

        // organise les modules parents avec les teaching block
        foreach ($topLevelModules as $module) {
            // chaque module est associe a un TB donc on recupere l'id du TB
            $blockId = $module->getTeachingBlock()->getId();

            $modulesByBlock[$blockId]['modules'][] = $module;
        }



        return $this->render('module/index.html.twig', [
            'modulesByBlock' => $modulesByBlock,
        ]);
    }

    // representation du tableau renvoyer
    // $modulesByBlock = [
    //     // Case pour le Bloc Informatique (ID 1)
    //     1 => [
    //         'block' => TeachingBlock { id: 1, name: "Informatique", code: "UE1" },
    //         'modules' => [
    //             0 => Module { id: 10, name: "Développement Web", parent: null },
    //             1 => Module { id: 11, name: "Base de données", parent: null },
    //             2 => Module { id: 15, name: "Réseaux", parent: null }
    //         ]
    //     ],
    // ];

    #[Route('/module/{id}/edit', name: 'app_module_edit')]
    public function edit($id, ModuleRepository $moduleRepository, TeachingBlockRepository $teachingBlockRepository): Response
    {

        return $this->render('module/edit.html.twig', [
            'hey' => 'hey',
        ]);
    }
}
