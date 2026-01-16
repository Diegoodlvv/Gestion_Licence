<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\Module;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\NewInstructorType;
use App\Form\InstructorFilterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\InstructorRepository;

class InstructorController extends AbstractController
{
    #[Route('/instructor', name: 'app_instructor')]
    public function index(Request $request, InstructorRepository $instructorRepository): Response
    {
        $form = $this->createForm(InstructorFilterType::class);
        $form->handleRequest($request);

        $instructors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $lastname = $form->get('lastname')->getData();
            $firstname = $form->get('firstname')->getData();
            $email = $form->get('email')->getData();

            // verifi si au moins un filtre est rempli
            if ($lastname || $firstname || $email) {
                $instructors = $instructorRepository->findByFilters($lastname, $firstname, $email);
            } else {
                $instructors = $instructorRepository->findAll();
            }
        } else {
            $instructors = $instructorRepository->findAll();
        }

        return $this->render('instructor/index.html.twig', [
            'instructors' => $instructors,
            'form' => $form,
            'count' => count($instructors)
        ]);
    }

    #[Route('/instructor/new', name: 'app_instructor_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $instructor = new Instructor();
        $form = $this->createForm(NewInstructorType::class, $instructor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // new user
                $user = new User();
                $user->setEmail($form->get('email')->getData());
                $user->setFirstname($form->get('firstname')->getData());
                $user->setLastname($form->get('lastname')->getData());
                $user->setRoles(['ROLE_USER']);

                // hash
                $password = $form->get('plainPassword')->getData();
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );

                $entityManager->persist($user);

                // lier l'utilisateur à l'instructeur
                $instructor->setUser($user);

                $entityManager->persist($instructor);
                $entityManager->flush();
                $this->addFlash('success', 'Enseignant ajouté avec succès !');
                return $this->redirectToRoute('app_instructor');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'ajout de l\'enseignant : ' . $e->getMessage());
                return $this->redirectToRoute('app_instructor');
            }
        }


        return $this->render('instructor/new.html.twig', [
            'form' => $form,
        ]);
    }
}
