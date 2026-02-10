<?php

namespace App\Form\Intervention;

use App\Entity\CoursePeriod;
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

class EditInterventionType extends AbstractType
{
    public function __construct(private readonly CoursePeriodRepository $course_period_repository) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $year = SchoolYear::getActualYear();

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'w-full flex px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => 'Cours sur ...'
                ],
            ])
            ->add('start_date', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => [
                    'data-controller' => 'flatpickr',
                    'placeholder' => sprintf('12 Janvier %s, ...', $year),
                    'data-flatpickr-min-time-value' => '08:00',
                    'data-flatpickr-max-time-value' => '17:30',
                    'data-flatpickr-min-date-value' => ($year - 1) . '-01-01',
                    'data-flatpickr-max-date-value' => ($year + 1) . '-12-31',
                    'data-flatpickr-disable-months-value' => json_encode([8]),
                ],
            ])
            ->add('end_date', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd HH:mm',
                'attr' => [
                    'data-controller' => 'flatpickr',
                    'placeholder' => sprintf('12 Janvier %s, ...', $year),
                    'data-flatpickr-min-time-value' => '08:00',
                    'data-flatpickr-max-time-value' => '17:30',
                    'data-flatpickr-min-date-value' => ($year - 1) . '-01-01',
                    'data-flatpickr-max-date-value' => ($year + 1) . '-12-31',
                    'data-flatpickr-disable-months-value' => json_encode([8]),
                ],
            ])
            ->add('remotely', CheckboxType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('intervention_type', EntityType::class, [
                'class' => InterventionType::class,
                'choice_label' => 'name',
                'placeholder' => "Seléctionnez un type d'intervention",
                'label' => "Type de l'intervention",
                'attr' => [
                    'data-controller' => 'intervention-type',
                    'data-intervention-type-target' => 'type'
                ],
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'FullName',
                'placeholder' => "Seléctionnez un module",
                'label' => "Module de l'intervention",
                'attr' => [],
            ])
            ->add('instructors', EntityType::class, [
                'class' => Instructor::class,
                'choice_label' => 'UserName',
                'required' => true,
                'label' => 'Instructeurs',
                'multiple' => true,
                'expanded' => false,
                'placeholder' => 'Sélectionnez des intervenants...',
                'attr' => [
                    'data-controller' => 'tom-select',
                ],
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
