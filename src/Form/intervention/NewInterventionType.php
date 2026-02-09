<?php

namespace App\Form\Intervention;

use App\Entity\Instructor;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Module;
use App\Entity\SchoolYear;
use App\Repository\CoursePeriodRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewInterventionType extends AbstractType
{
    public function __construct(private readonly CoursePeriodRepository $course_period_repository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $year = SchoolYear::getActualYear();

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'w-full flex px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => 'Cours sur ...',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
           ->add('start_date', DateTimeType::class, [
               'label' => 'Date de début - champ obligatoire',
               'widget' => 'choice',
               'hours' => [8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
               'minutes' => [0, 30],
               'months' => [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12],
               'years' => [$year, $year - 1, $year + 1],
               'attr' => [
                   'class' => 'w-full flex px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                   'placeholder' => '12 Janvier 2026, ...',
               ],
               'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
           ])
            ->add('end_date', DateTimeType::class, [
                'label' => 'Date de fin - champ obligatoire',
                'widget' => 'choice',
                'hours' => [8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                'minutes' => [0, 30],
                'months' => [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12],
                'years' => [$year, $year - 1, $year + 1],
                'attr' => [
                    'class' => 'w-full flex px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => '12 Janvier 2026, ...',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
           ->add('remotely', CheckboxType::class, [
               'label' => false,
               'required' => false,
           ])
            ->add('intervention_type', EntityType::class, [
                'class' => InterventionType::class,
                'choice_label' => 'name',
                'placeholder' => "Seléctionnez un type d'intervention",
                'label' => "Type de l'intervention - champ obligatoire",
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'data-controller' => 'intervention-type',
                    'data-intervention-type-target' => 'type',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'FullName',
                'placeholder' => 'Seléctionnez un module',
                'label' => "Module de l'intervention - champ obligatoire",
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('instructors', EntityType::class, [
                'class' => Instructor::class,
                'choice_label' => 'UserName',
                'required' => false,
                'label' => 'Instructeurs - champ obligatoire',
                'multiple' => true,
                'expanded' => false,
                'placeholder' => 'Sélectionnez des intervenants...',
                'attr' => [
                    'class' => 'w-full py-2',
                    'data-controller' => 'tom-select',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (PostSubmitEvent $event) {
                $intervention = $event->getData();

                if (!$intervention) {
                    return;
                }

                $period = $this->course_period_repository->queryCoursePeriod(
                    $intervention->getStartDate(),
                    $intervention->getEndDate()
                );

                $intervention->setCoursePeriod($period);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
