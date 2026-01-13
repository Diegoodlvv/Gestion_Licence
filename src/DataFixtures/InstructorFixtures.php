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
                'firstname' => 'Sonia',
                'lastname' => 'Aracil',
                'modules' => [
                    [
                        "name" => "Javascript"
                    ]
                ]
            ],
            [
                'firstname' => 'Virginie',
                'lastname' => 'Hougron',
                'modules' => [
                    [
                        "name" => "Javascript"
                    ]
                ]
            ],
            [
                'firstname' => 'Charles',
                'lastname' => 'Haller',
                'modules' => [
                    [
                        "name" => "Javascript"
                    ]
                ]
            ],
            [
                'firstname' => 'Nicolas',
                'lastname' => 'Castro',
                'modules' => [
                    [
                        "name" => "Javascript"
                    ]
                ]
            ],
        ];

        foreach ($data as $entry) {
            // creation user
            $user = new User();
            $email = strtolower($entry['firstname'] . '.' . $entry['lastname'] . '@gmail.com');
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($entry['firstname']);
            $user->setLastname($entry['lastname']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            // lier l'utilisateur à l'instructeur
            $instructor = new Instructor();
            $instructor->setUser($user);

            foreach ($entry['modules'] as $userModuleName) {
                $module = $this->getReference($userModuleName["name"], Module::class);

                if ($module) {
                    $instructor->addModule($module);
                }
            }

            $manager->persist($instructor);
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
