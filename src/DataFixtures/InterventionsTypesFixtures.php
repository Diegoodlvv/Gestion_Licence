<?php

namespace App\DataFixtures;

use App\Entity\InterventionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InterventionsTypesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            [
                "name" => "Autonomie",
                "description" => "Elèves en autonomie",
                "color" => "#6750A4"
            ],
            [
                "name" => "Conférence",
                "description" => "Conférence effectué par un ou plusieurs intervenants",
                "color" => "#028202"
            ],
            [
                "name" => "Cours",
                "description" => "Cours dispensé par un ou plusieurs intervenants",
                "color" => "#0EA5E9"
            ],
            [
                "name" => "Evaluation",
                "description" => "Evaluation sous form de POC ou d'écrit",
                "color" => "#FF8000"
            ],
            [
                "name" => "Soutenance",
                "description" => "Soutenance de fin de projet",
                "color" => "#8C1D18"
            ],
        ];

        foreach ($types as $data) {
            $type = new InterventionType();
            $type->setName($data["name"]);
            $type->setDescription($data['description']);
            $type->setColor($data['color']);

            $manager->persist($type);
        }

        $manager->flush();
    }
}
