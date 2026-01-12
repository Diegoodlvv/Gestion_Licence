<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // creer un utilisateur admin
        $admin = new User();
        $admin->setEmail('toto@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setLastname('Toto');
        $admin->setFirstname('Toto');

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'toto');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);
        $manager->flush();

        // creation d'un utilisateur normal
        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setLastname('User');
        $user->setFirstname('User');

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();
    }
}
