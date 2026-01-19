<?php

namespace App\Form;

use App\Entity\CoursePeriod;
use App\Entity\Instructor;
use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Entity\Module;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionsFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateTimeType::class, [
                'label' => 'Date de début',
                'required' => false,
                'attr' => [
                    'class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all'
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700 mb-1'
                ]
            ])
            ->add('end_date', DateTimeType::class, [
                'label' => 'Date de début',
                'required' => false,
                'attr' => [
                    'class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all'
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700 mb-1'
                ]
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'name',
                'label' => 'Module',
                'required' => false,
                'placeholder' => 'Sélectionnez le module',
                'attr' => [
                    'class' => 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all'
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700 mb-1'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'validation_groups' => ['filter']
        ]);
    }
}
