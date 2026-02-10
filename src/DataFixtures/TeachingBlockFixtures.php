<?php

namespace App\DataFixtures;

use App\Entity\TeachingBlock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeachingBlockFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $blocks = [
            [
                'code' => 'B1',
                'name' => 'Piloter un projet informatique',
                'description' => 'Piloter un projet informatique',
                'hours_count' => 87.5,
            ],
            [
                'code' => 'B2',
                'name' => 'Coordonner une equipe de projet',
                'description' => 'Coordoner une équipe projet',
                'hours_count' => 105,
            ],
            [
                'code' => 'B3',
                'name' => "Superviser la mise en oeuvre d'un projet informatique",
                'description' => "Superviser la mise en oeuvre d'un projet informatique",
                'hours_count' => 14,
            ],
            [
                'code' => 'B4',
                'name' => 'Coordonner le cycle de vie des applications',
                'description' => 'Coordoner le cycle de vie des applications',
                'hours_count' => 297.5,
            ],
        ];

        foreach ($blocks as $data) {
            $block = new TeachingBlock();
            $block->setCode($data['code'])
                ->setName($data['name'])
                ->setDescription($data['description'])
                ->setHoursCount($data['hours_count']);

            $manager->persist($block);

            $this->addReference($data['code'], $block);
        }

        $manager->flush();
    }
}
