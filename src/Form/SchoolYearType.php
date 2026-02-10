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
                'label' => 'Année - champ obligatoire',
                'attr' => [
                    'placeholder' => '2024'
                ],
            ])
            ->add('saison', TextType::class, [
                'label' => 'Saison - champ obligatoire',
                'attr' => [
                    'placeholder' => '2024-2025'
                ]
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
