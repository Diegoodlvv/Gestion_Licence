<?php

namespace App\DataFixtures;

use App\Entity\Instructor;
use App\Entity\Module;
use App\Entity\TypeContrat;
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
                'typeContrat' => 'Vacataire',
                'modules' => [
                    [
                        'name' => 'Gestion de projet - Méthode Agile',
                    ],
                ],
            ],
            [
                'firstname' => 'Gael',
                'lastname' => 'Daniel',
                'typeContrat' => 'Intervenant professionnel',
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
                'typeContrat' => 'Titulaire',
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
                'typeContrat' => 'Vacataire',
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
                'typeContrat' => 'Intervenant professionnel',
                'modules' => [
                    [
                        'name' => 'Devops/Cyber',
                    ],
                ],
            ],
            [
                'firstname' => 'Cyril',
                'lastname' => 'Peireira',
                'typeContrat' => 'Titulaire',
                'modules' => [
                    [
                        'name' => 'Conférence',
                    ],
                ],
            ],
            [
                'firstname' => 'Virginie',
                'lastname' => 'Hougron',
                'typeContrat' => 'Vacataire',
                'modules' => [
                    [
                        'name' => "Rédaction de comptes rendus d'activités",
                    ],
                ],
            ],
            [
                'firstname' => 'Brigitte',
                'lastname' => 'Esquenet',
                'typeContrat' => 'Intervenant professionnel',
                'modules' => [
                    [
                        'name' => 'Anglais - Préparation au TOIEC',
                    ],
                ],
            ],
            [
                'firstname' => 'Sonia',
                'lastname' => 'Aracil',
                'typeContrat' => 'Titulaire',
                'modules' => [
                    [
                        'name' => "Les fondamentaux de l'UX",
                    ],
                ],
            ],
            [
                'firstname' => 'Olivier',
                'lastname' => 'Salesse',
                'typeContrat' => 'Vacataire',
                'modules' => [
                    [
                        'name' => "L'UI et L'UX en mode projet",
                    ],
                ],
            ],
            [
                'firstname' => 'Christopher',
                'lastname' => 'Espargelière',
                'typeContrat' => 'Intervenant professionnel',
                'modules' => [
                    [
                        'name' => 'Tailwind css',
                    ],
                ],
            ],
            [
                'firstname' => 'Charles',
                'lastname' => 'Haller',
                'typeContrat' => 'Titulaire',
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
                'typeContrat' => 'Vacataire',
                'modules' => [
                    [
                        'name' => 'Javascript',
                    ],
                ],
            ],
            [
                'firstname' => 'Nicolas',
                'lastname' => 'Castro',
                'typeContrat' => 'Intervenant professionnel',
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

            $typeContrat = $this->getReference(TypeContratFixtures::REFERENCE_PREFIXE.$entry['typeContrat'], TypeContrat::class);
            $instructor->setTypeContrat($typeContrat);

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
            TypeContratFixtures::class,
        ];
    }
}
