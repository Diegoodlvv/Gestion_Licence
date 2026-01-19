<?php

namespace App\Form;

use App\Entity\SchoolYear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolYearType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year', TextType::class, [
                'label' => 'Année',
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => '2024'
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
            ->add('saison', TextType::class, [
                'label' => 'Saison',
                'attr' => [
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring focus:ring-black focus:border-black',
                    'placeholder' => '2024-2025'
                ],
                'label_attr' => ['class' => 'block text-sm text-slate-700 mb-1'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SchoolYear::class,
        ]);
    }
}
