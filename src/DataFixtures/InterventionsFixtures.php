<?php

namespace App\DataFixtures;

use App\Entity\CoursePeriod;
use App\Entity\Instructor;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Module;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InterventionsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $interventions = [
            [
                "start_date" => new DateTime("10/12/2025 13:30"),
                "end_date" => new DateTime("10/12/2025 17:30"),
                "remotely" => 1,
                "course_period_id" => 3,
                "intervention_type_id" => "Cours",
                "module_id" => "Gestion de projet - Méthode agile",
                "instructor" => [
                    "Aste"
                ]
            ],
            [
                "start_date" => new DateTime("8/12/2025 8:30"),
                "end_date" => new DateTime("8/12/2025 12:30"),
                "remotely" => 0,
                "course_period_id" => 3,
                "intervention_type_id" => "Cours",
                "module_id" => "Eco-Conception",
                "instructor" => [
                    "Knorr"
                ]
            ],
            [
                "start_date" => new DateTime("9/12/2025 13:30"),
                "end_date" => new DateTime("9/12/2025 17:30"),
                "remotely" => 1,
                "course_period_id" => 3,
                "intervention_type_id" => "Cours",
                "module_id" => "Devops/Cyber",
                "instructor" => [
                    "Delsaux",
                    "Martins-Jacquelot"
                ]
            ],
            [
                "start_date" => new DateTime("9/12/2025 8:30"),
                "end_date" => new DateTime("9/12/2025 12:30"),
                "remotely" => 1,
                "course_period_id" => 3,
                "intervention_type_id" => "Autonomie",
                "module_id" => "Javascript",
                "instructor" => null
            ],
        ];


        foreach ($interventions as $data) {
            $interventions = new Intervention();
            $interventions->setStartDate($data["start_date"]);
            $interventions->setEndDate($data("end_date"));
            $interventions->setRemotely($data["remotely"]);
            $interventions->setCoursePeriod($this->getReference($data["course_period_id"], CoursePeriod::class));
            $interventions->setInterventonType($this->getReference($data["intervention_type_id"], InterventionType::class));
            $interventions->setModule($this->getReference($data["module_id"], Module::class));

            if ($data["instructor"]) {
                foreach ($data["instructor"] as $datainstructors) {
                    $interventions->addInstructor($this->getReference($datainstructors, Instructor::class));
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CoursePeriodFixtures::class,
            InterventionsFixtures::class,
            ModuleFixtures::class
        ];
    }
}
