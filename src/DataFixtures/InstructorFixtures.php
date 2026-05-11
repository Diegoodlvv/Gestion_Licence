<?php

namespace App\DataFixtures;

use App\Entity\Instructor;
use App\Entity\Module;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InstructorFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'firstname' => 'Dominique',
                'lastname' => 'Aste',
                'modules' => [
                    [
                        'name' => 'Gestion de projet - Méthode Agile',
                    ],
                ],
            ],
            [
                'firstname' => 'Gael',
                'lastname' => 'Daniel',
                'modules' => [
                    [
                        'name' => 'Cadre légal - Droit numérique',
                    ],
                    [
                        'name' => 'RGPD',
                    ],
                    [
                        'name' => 'Propriété Intellectuelle',
                    ],
                    [
                        'name' => 'RSE',
                    ],
                    [
                        'name' => 'Accessibilité',
                    ],
                ],
            ],
            [
                'firstname' => 'Hugo',
                'lastname' => 'Knorr',
                'modules' => [
                    [
                        'name' => 'Eco-Conception',
                    ],
                    [
                        'name' => 'React',
                    ],
                    [
                        'name' => 'Symfony',
                    ],
                ],
            ],
            [
                'firstname' => 'Jeff',
                'lastname' => 'Martins-Jacquelot',
                'modules' => [
                    [
                        'name' => 'Environnement de travail',
                    ],
                    [
                        'name' => 'Environnement de production',
                    ],
                    [
                        'name' => 'Docker',
                    ],
                    [
                        'name' => 'Git',
                    ],
                ],
            ],
            [
                'firstname' => 'Maxime',
                'lastname' => 'Delsaux',
                'modules' => [
                    [
                        'name' => 'Devops/Cyber',
                    ],
                ],
            ],
            [
                'firstname' => 'Cyril',
                'lastname' => 'Peireira',
                'modules' => [
                    [
                        'name' => 'Conférence',
                    ],
                ],
            ],
            [
                'firstname' => 'Virginie',
                'lastname' => 'Hougron',
                'modules' => [
                    [
                        'name' => "Rédaction de comptes rendus d'activités",
                    ],
                ],
            ],
            [
                'firstname' => 'Brigitte',
                'lastname' => 'Esquenet',
                'modules' => [
                    [
                        'name' => 'Anglais - Préparation au TOIEC',
                    ],
                ],
            ],
            [
                'firstname' => 'Sonia',
                'lastname' => 'Aracil',
                'modules' => [
                    [
                        'name' => "Les fondamentaux de l'UX",
                    ],
                ],
            ],
            [
                'firstname' => 'Olivier',
                'lastname' => 'Salesse',
                'modules' => [
                    [
                        'name' => "L'UI et L'UX en mode projet",
                    ],
                ],
            ],
            [
                'firstname' => 'Christopher',
                'lastname' => 'Espargelière',
                'modules' => [
                    [
                        'name' => 'Tailwind css',
                    ],
                ],
            ],
            [
                'firstname' => 'Charles',
                'lastname' => 'Haller',
                'modules' => [
                    [
                        'name' => 'Mise à niveau PHP',
                    ],
                    [
                        'name' => 'PHP Objet',
                    ],
                ],
            ],
            [
                'firstname' => 'Nicolas',
                'lastname' => 'Pineau',
                'modules' => [
                    [
                        'name' => 'Javascript',
                    ],
                ],
            ],
            [
                'firstname' => 'Nicolas',
                'lastname' => 'Castro',
                'modules' => [
                    [
                        'name' => 'NextJS',
                    ],
                ],
            ],
        ];

        foreach ($data as $entry) {
            // creation user
            $user = new User();
            $email = strtolower($entry['firstname'].'.'.$entry['lastname'].'@gmail.com');
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($entry['firstname']);
            $user->setLastname($entry['lastname']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            $instructor = new Instructor();
            $instructor->setUser($user);

            foreach ($entry['modules'] as $userModuleName) {
                $module = $this->getReference($userModuleName['name'], Module::class);

                if ($module) {
                    $instructor->addModule($module);
                }
            }

            $manager->persist($instructor);

            $this->addReference($entry['lastname'], $instructor);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ModuleFixtures::class,
        ];
    }
}
