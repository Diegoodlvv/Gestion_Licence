<?php

namespace App\Form;

use App\Entity\CoursePeriod;
use App\Entity\SchoolYear;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursePeriodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('schoolYear', EntityType::class, [
                'class' => SchoolYear::class,
                'choice_label' => 'year',
                'disabled' => true,
                'label' => 'Année - champ obligatoire',
                'attr' => [
                    'class' => 'w-full px-3 py-2 border-slate-200 rounded-md bg-gray-50 text-slate-500 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])

            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début - champ obligatoire',
                'input' => 'datetime',
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin - champ obligatoire',
                'input' => 'datetime',
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoursePeriod::class,
        ]);
    }
}
