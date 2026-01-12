<?php

namespace App\DataFixtures;

use App\Entity\SchoolYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SchoolYearFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $years = [
            [
                "year" => "2025",
                "saison" => "2025/2026"
            ],
            [
                "year" => "2026",
                "saison" => "2026/2027"
            ],
            [
                "year" => "2027",
                "saison" => "2027/2028"
            ]
        ];

        foreach ($years as $data) {
            $year = new SchoolYear();
            $year->setYear($data["year"]);
            $year->setSaison($data["saison"]);

            $manager->persist($year);

            $this->addReference($data['year'], $year);
        }

        $manager->flush();
    }
}
