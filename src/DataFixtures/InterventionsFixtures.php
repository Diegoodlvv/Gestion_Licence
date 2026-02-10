<?php

namespace App\DataFixtures;

use App\Entity\CoursePeriod;
use App\Entity\Instructor;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Module;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InterventionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $interventions = [
            [
                'title' => 'Spé techniques',
                'start_date' => new \DateTime('10/12/2025 13:30'),
                'end_date' => new \DateTime('10/12/2025 17:30'),
                'remotely' => 1,
                'course_period_id' => '2025_12_w2',
                'intervention_type_id' => 'Cours',
                'module_id' => 'Gestion de projet - Méthode Agile',
                'instructor' => [
                    'name' => 'Aste',
                ],
            ],
            [
                'title' => 'Eco-Conception',
                'start_date' => new \DateTime('8/12/2025 8:30'),
                'end_date' => new \DateTime('8/12/2025 12:30'),
                'remotely' => 0,
                'course_period_id' => '2025_12_w2',
                'intervention_type_id' => 'Cours',
                'module_id' => 'Eco-Conception',
                'instructor' => [
                    'name' => 'Knorr',
                ],
            ],
            [
                'title' => 'Cours sur VPS',
                'start_date' => new \DateTime('9/12/2025 13:30'),
                'end_date' => new \DateTime('9/12/2025 17:30'),
                'remotely' => 1,
                'course_period_id' => '2025_12_w2',
                'intervention_type_id' => 'Cours',
                'module_id' => 'Devops/Cyber',
                'instructor' => [
                    [
                        'name' => 'Martins-Jacquelot',
                    ],
                    [
                        'name' => 'Delsaux',
                    ],
                ],
            ],
            [
                'title' => 'Projet en autonomie',
                'start_date' => new \DateTime('9/12/2025 8:30'),
                'end_date' => new \DateTime('9/12/2025 12:30'),
                'remotely' => 1,
                'course_period_id' => '2025_12_w2',
                'intervention_type_id' => 'Autonomie',
                'module_id' => 'Javascript',
                'instructor' => null,
            ],
        ];

        foreach ($interventions as $data) {
            $intervention = new Intervention();
            $intervention->setTitle($data['title']);
            $intervention->setStartDate($data['start_date']);
            $intervention->setEndDate($data['end_date']);
            $intervention->setRemotely($data['remotely']);
            $intervention->setCoursePeriod($this->getReference($data['course_period_id'], CoursePeriod::class));
            $intervention->setInterventionType($this->getReference($data['intervention_type_id'], InterventionType::class));
            $intervention->setModule($this->getReference($data['module_id'], Module::class));

            $instructors = $data['instructor'];

            if ($instructors && isset($instructors['name'])) {
                $instructors = [$instructors];
            }

            if ($instructors) {
                foreach ($instructors as $datainstructors) {
                    $intervention->addInstructor(
                        $this->getReference($datainstructors['name'], Instructor::class)
                    );
                }
            }

            $manager->persist($intervention);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CoursePeriodFixtures::class,
            ModuleFixtures::class,
            InstructorFixtures::class,
        ];
    }
}
