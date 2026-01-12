<?php

namespace App\DataFixtures;

use App\Entity\CoursePeriod;
use App\Entity\SchoolYear;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CoursePeriodFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $periods = [
            [
                "start_date" => new DateTime("01/09/2025"),
                "end_date" => new DateTime("12/09/2025"),
                "school_year" => "2025"
            ],
            [
                "start_date" => new DateTime("06/10/2025"),
                "end_date" => new DateTime("10/10/2025"),
                "school_year" => "2025"
            ],
            [
                "start_date" => new DateTime("03/11/2025"),
                "end_date" => new DateTime("07/11/2025"),
                "school_year" => "2025"
            ]
        ];

        foreach ($periods as $data) {
            $period = new CoursePeriod();
            $period->setStartDate($data["start_date"]);
            $period->setEndDate($data["end_date"]);
            $period->setSchoolYearId($this->getReference($data["school_year"], SchoolYear::class));

            $manager->persist($period);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SchoolYearFixtures::class
        ];
    }
}
