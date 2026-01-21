<?php

namespace App\Form;

use App\Entity\CoursePeriod;
use App\Entity\Instructor;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Module;
use DateTime;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('start_date', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => [
                    'class' => 'js-datetime-start w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => '12 Janvier 2026, ...'
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('end_date', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => [
                    'class' => 'js-datetime-end w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => '12 Janvier 2026, ...'
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
           ->add('remotely', CheckboxType::class, [
                'label' => false, 
                'required' => false,
            ])
            ->add('course_period', EntityType::class, [
                'class' => CoursePeriod::class,
                'choice_label' => 'getCoursePeriod',
                'label' => "Semaine d'interventions",
                'placeholder' => 'Sélectionnez une période',
                'attr' => [
                    'class' => 'js-course-period w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => 'Sélectionnez une période',
                    ],
                'choice_attr' => function($period) {
                    return [
                        'data-start' => $period->getStartDate()->format('Y-m-d H:i'),
                        'data-end' => $period->getEndDate()->format('Y-m-d H:i'),
                    ];
                },
            ])
            ->add('intervention_type', EntityType::class, [
                'class' => InterventionType::class,
                'choice_label' => 'name',
                'placeholder' => "Seléctionnez un type d'intervention",
                'label' => "Type de l'intervention",
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'data-controller' => 'intervention-type',
                    'data-intervention-type-target' => 'type'
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'FullName',
                'placeholder' => "Seléctionnez un module",
                'label' => "Module de l'intervention",
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('instructors', EntityType::class, [
                'class' => Instructor::class,
                'choice_label' => 'UserName',
                'label' => 'Instructeurs',
                'multiple' => true,
                'expanded' => false,
                'placeholder' => 'Sélectionnez des intervenants...',
                'attr' => [
                    'class' => "w-full py-2",
                    'data-controller' => 'tom-select',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
