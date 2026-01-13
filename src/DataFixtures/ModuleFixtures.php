<?php

namespace App\DataFixtures;

use App\Entity\Module;
use App\Entity\TeachingBlock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModuleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $modules = [
            //B1
            [
                "code" => "GESTION_PROJET_AGILE",
                "name" => "Gestion de projet - Méthode Agile",
                "description" => "Piloter un projet informatique",
                "hours_count" => 63,
                "capstone_project" => 0,
                "children" => null,
                "teaching_block" => 'B1'
            ],
            [
                "code" => "DROIT_NUMERIQUE",
                "name" => "Cadre légal - Droit numérique",
                "description" => "Piloter un projet informatique",
                "hours_count" => 21,
                "capstone_project" => 0,
                "children" => [
                    [
                        "code" => "RGPD",
                        "name" => "RGPD",
                        "description" => "Piloter un projet informatique",
                        "hours_count" => 0,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B1'
                    ],
                    [
                        "code" => "PROPRIETE_INTELLECTUELLE",
                        "name" => "Propriéte intellectuelle",
                        "description" => "Piloter un projet informatique",
                        "hours_count" => 0,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B1'
                    ],
                    [
                        "code" => "RSE",
                        "name" => "RSE",
                        "description" => "Piloter un projet informatique",
                        "hours_count" => 0,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B1'
                    ],
                    [
                        "code" => "ACCESSIBILITE",
                        "name" => "accessiblité",
                        "description" => "Piloter un projet informatique",
                        "hours_count" => 0,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B1'
                    ]
                ],
                "teaching_block" => 'B1'
            ],
            [
                "code" => "ECO_CONCEPTION",
                "name" => "Eco-Conception",
                "description" => "Piloter un projet informatique",
                "hours_count" => 3.5,
                "capstone_project" => 0,
                "children" => null,
                "teaching_block" => 'B1'
            ],

            // B2 

            [
                "code" => "PREPARATION_TOIC",
                "name" => "Anglais - Préparation au TOEIC",
                "description" => "Coordoner une équipe projet",
                "hours_count" => 17.5,
                "capstone_project" => 0,
                "children" => null,
                "teaching_block" => 'B2'
            ],
            [
                "code" => "COMMUNICATION_SKILLS",
                "name" => "Communication - Soft Skills",
                "description" => "Coordoner une équipe projet",
                "hours_count" => 28,
                "capstone_project" => 0,
                "children" => null,
                "teaching_block" => 'B2'
            ],
            [
                "code" => "DEVOPS_CYBER",
                "name" => "Devops et Cybersécurité",
                "description" => "Coordoner une équipe projet",
                "hours_count" => 58,
                "capstone_project" => 0,
                "children" => [
                    [
                        "code" => "ENVIRONNEMENT_TRAVAIL",
                        "name" => "Environnement de travail",
                        "description" => "Coordoner une équipe projet",
                        "hours_count" => 7,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B2'
                    ],
                    [
                        "code" => "ENVIRONNEMENT_PROD",
                        "name" => "Environnement de production",
                        "description" => "Coordoner une équipe projet",
                        "hours_count" => 7,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B2'
                    ],
                    [
                        "code" => "DOCKER",
                        "name" => "Docker",
                        "description" => "Coordoner une équipe projet",
                        "hours_count" => 14,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B2'
                    ],
                    [
                        "code" => "GIT",
                        "name" => "Git",
                        "description" => "Coordoner une équipe projet",
                        "hours_count" => 7,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B2'
                    ],
                    [
                        "code" => "DEV_CYB",
                        "name" => "Devops/Cyber",
                        "description" => "Coordoner une équipe projet",
                        "hours_count" => 21,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B2'
                    ],
                ],
                "teaching_block" => 'B2'
            ],
            [
                "code" => "RETOUR_EXP",
                "name" => "Retour d'expérience (REX)",
                "description" => "Coordoner une équipe projet",
                "hours_count" => 3.5,
                "capstone_project" => 0,
                "children" => [
                    [
                        "code" => "CONF",
                        "name" => "Conférence",
                        "description" => "Coordoner une équipe projet",
                        "hours_count" => 3.5,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B2'
                    ]
                ],
                "teaching_block" => 'B2'
            ],

            // B3

            [
                "code" => "REDAC_COMPTERENDUS",
                "name" => "Rédaction de comptes rendus d'activités",
                "description" => "Superviser la mise en oeuvre d'un projet informatique",
                "hours_count" => 14,
                "capstone_project" => 0,
                "children" => null,
                "teaching_block" => 'B3'
            ],

            // B4 

            [
                "code" => "ERGONOMIE_MAQUETTAGE",
                "name" => "Ergonomie et maquettage des applications",
                "description" => "Coordonner le cycle de vie des applications",
                "hours_count" => 49,
                "capstone_project" => 0,
                "children" => [
                    [
                        "code" => "USEREXP_USERINT",
                        "name" => "User Expérience / User Interface",
                        "description" => "Superviser la mise en oeuvre d'un projet informatique",
                        "hours_count" => 49,
                        "capstone_project" => 0,
                        "children" => [
                            [
                                "code" => "UX_FONDAMENTAUX",
                                "name" => "Les fondamentaux de l'UX",
                                "description" => "Superviser la mise en oeuvre d'un projet informatique",
                                "hours_count" => 21,
                                "capstone_project" => 0,
                                "children" => null,
                                "teaching_block" => 'B4'
                            ],
                            [
                                "code" => "UXUI_PROJET",
                                "name" => "L'UI et l'UX en mode projet",
                                "description" => "Superviser la mise en oeuvre d'un projet informatique",
                                "hours_count" => 28,
                                "capstone_project" => 0,
                                "children" => null,
                                "teaching_block" => 'B4'
                            ],
                        ],
                        "teaching_block" => 'B4'
                    ],
                ],
                "teaching_block" => 'B4'
            ],
            [
                "code" => "ARCHITECTURE_DONNEES",
                "name" => "Architecture des données",
                "description" => "Coordonner le cycle de vie des applications",
                "hours_count" => 10.5,
                "capstone_project" => 0,
                "children" => [
                    [
                        "code" => "STRUCTURE_BDD",
                        "name" => "Structurer et mettre en place une architecture de base de données",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 7,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                    [
                        "code" => "MONITORER_BDD",
                        "name" => "Monitorer une base de données + perfomance ",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 3.5,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                ],
                "teaching_block" => 'B4'
            ],
            [
                "code" => "DEV_FRONT",
                "name" => "Développement front",
                "description" => "Coordonner le cycle de vie des applications",
                "hours_count" => 126,
                "capstone_project" => 0,
                "children" => [
                    [
                        "code" => "TAILWIND_CSS",
                        "name" => "Tailwind CSS",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 14,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                    [
                        "code" => "JAVASCRIPT",
                        "name" => "Javascript",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 35,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                    [
                        "code" => "REACTJS",
                        "name" => "React",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 49,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                    [
                        "code" => "NEXTJS",
                        "name" => "NextJS",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 28,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                ],
                "teaching_block" => 'B4'
            ],
            [
                "code" => "DEV_BACK",
                "name" => "Développement back",
                "description" => "Coordonner le cycle de vie des applications",
                "hours_count" => 112,
                "capstone_project" => 0,
                "children" => [
                    [
                        "code" => "MAN_PHP",
                        "name" => "Mise à niveau de PHP",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 21,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                    [
                        "code" => "PHPO",
                        "name" => "PHP Objet",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 28,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                    [
                        "code" => "SYMFONY",
                        "name" => "Symfony",
                        "description" => "Coordonner le cycle de vie des applications",
                        "hours_count" => 63,
                        "capstone_project" => 0,
                        "children" => null,
                        "teaching_block" => 'B4'
                    ],
                ],
                "teaching_block" => 'B4'
            ],
        ];


        foreach ($modules as $data) {
            $module = new Module();
            $module->setCode($data["code"]);
            $module->setName($data["name"]);
            $module->setDescription($data["description"]);
            $module->setHoursCount($data["hours_count"]);
            $module->setCapstoneProject($data["capstone_project"]);
            $module->setTeachingBlock($this->getReference($data["teaching_block"], TeachingBlock::class));
            if (($data["children"])) {
                foreach ($data["children"] as $datachild) {
                    $child = new Module();
                    $child->setCode($datachild["code"]);
                    $child->setName($datachild["name"]);
                    $child->setDescription($datachild["description"]);
                    $child->setHoursCount($datachild["hours_count"]);
                    $child->setCapstoneProject($datachild["capstone_project"]);
                    $child->setTeachingBlock($this->getReference($datachild["teaching_block"], TeachingBlock::class));
                    $module->addModulesChild($child);

                    if (($datachild["children"])) {
                        foreach ($datachild["children"] as $childDataChild) {
                            $littleChild = new Module();
                            $littleChild->setCode($childDataChild["code"]);
                            $littleChild->setName($childDataChild["name"]);
                            $littleChild->setDescription($childDataChild["description"]);
                            $littleChild->setHoursCount($childDataChild["hours_count"]);
                            $littleChild->setCapstoneProject($childDataChild["capstone_project"]);
                            $littleChild->setTeachingBlock($this->getReference($childDataChild["teaching_block"], TeachingBlock::class));
                            $child->addModulesChild($littleChild);
                        }
                    }
                }
            }


            $manager->persist($module);

            $this->addReference($data["name"], $module);
            $this->addReference($datachild["name"], $child);
            $this->addReference($childDataChild["name"], $littleChild);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TeachingBlockFixtures::class
        ];
    }
}
