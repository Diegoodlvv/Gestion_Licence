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
use App\Form\EditInstructorType;
use App\Form\InstructorInterventionsFilterType;
use Knp\Component\Pager\PaginatorInterface;

class InstructorController extends AbstractController
{
    #[Route('/instructor', name: 'app_instructor')]
    public function index(Request $request, InstructorRepository $instructorRepository, PaginatorInterface $pagination): Response
    {
        $form = $this->createForm(InstructorFilterType::class);
        $form->handleRequest($request);

        $instructors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $lastname = $form->get('lastname')->getData();
            $firstname = $form->get('firstname')->getData();
            $email = $form->get('email')->getData();

            if ($lastname || $firstname || $email) {
                $instructors = $instructorRepository->queryInstructorByFilters($lastname, $firstname, $email);
            } else {
                $instructors = $instructorRepository->findAll();
            }
        } else {
            $instructors = $instructorRepository->findAll();
        }

          $pagination = $pagination->paginate(
            $instructors,
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('instructor/index.html.twig', [
            'instructors' => $pagination,
            'form' => $form,
        ]);
    }

    #[Route('/instructor/{id}/show_interventions', name: 'app_instructor_interventions')]
    public function showInterventions($id, InstructorRepository $instructorRepository, Request $request, PaginatorInterface $pagination): Response
    {
        $instructor = $instructorRepository->find($id);
        $form = $this->createForm(InstructorInterventionsFilterType::class);
        $form->handleRequest($request);

        $interventions = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $start_date = $form->get('start_date')->getData();
            $end_date = $form->get('end_date')->getData();
            $module = $form->get('module')->getData();

            if ($start_date || $end_date || $module) {
                $interventions = $instructorRepository->queryInstructorInterventionsByFilters($id, $start_date, $end_date, $module);
            }

        } else {
            $interventions = $instructorRepository->queryInstructorInterventionsByFilters($id, null, null, null);
        }

            $pagination = $pagination->paginate(
            $interventions,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('instructor/interventions.html.twig', [
            'interventions' => $pagination,
            'form' => $form,
            'instructor' => $instructor
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

    #[Route('/instructor/{id}/edit', name: 'app_instructor_edit')]
    public function edit($id, InstructorRepository $instructorRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $instructor = $instructorRepository->find($id);
        $form = $this->createForm(EditInstructorType::class, $instructor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Enseignant modifié avec succès !');
                return $this->redirectToRoute('app_instructor');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'enseignant : ' . $e->getMessage());
                return $this->redirectToRoute('app_instructor');
            }
        }



        return $this->render('instructor/edit.html.twig', [
            'form' => $form,
            'instructor' => $instructor
        ]);
    }

    #[Route('/instructor/{id}/delete', name: 'app_instructor_delete')]
    public function delete($id, InstructorRepository $instructorRepository, EntityManagerInterface $entityManager)
    {
        $instructor = $instructorRepository->find($id);

        if ($instructor) {
            $entityManager->remove($instructor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_instructor');
    }
}
